<?php

namespace App\Services;

use ZipArchive;
use DOMDocument;
use DOMXPath;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class DocxQuestionParser
{
    /**
     * Parse the DOCX file and return an array of question data.
     *
     * @param string $filePath Absolute path to the DOCX file
     * @return array
     * @throws \Exception
     */
    public static function parse(string $filePath): array
    {
        $zip = new ZipArchive();
        if ($zip->open($filePath) !== true) {
            throw new \Exception("Unable to open DOCX file. Make sure it is a valid ZIP/DOCX archive.");
        }

        // 1. Read relationships XML to map image rIds to filenames
        $rels = [];
        $relsXml = $zip->getFromName('word/_rels/document.xml.rels');
        if ($relsXml) {
            $dom = new DOMDocument();
            @$dom->loadXML($relsXml, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
            foreach ($dom->getElementsByTagName('Relationship') as $rel) {
                $id = $rel->getAttribute('Id');
                $type = $rel->getAttribute('Type');
                $target = $rel->getAttribute('Target');
                if (strpos($type, 'relationships/image') !== false) {
                    $rels[$id] = $target;
                }
            }
        }

        // 2. Read the main document XML
        $docXml = $zip->getFromName('word/document.xml');
        if (!$docXml) {
            $zip->close();
            throw new \Exception("Invalid DOCX format: word/document.xml not found.");
        }

        $dom = new DOMDocument();
        @$dom->loadXML($docXml, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
        $xpath = new DOMXPath($dom);
        $xpath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');
        $xpath->registerNamespace('r', 'http://schemas.openxmlformats.org/officeDocument/2006/relationships');
        $xpath->registerNamespace('a', 'http://schemas.openxmlformats.org/drawingml/2006/main');
        $xpath->registerNamespace('pic', 'http://schemas.openxmlformats.org/drawingml/2006/picture');
        $xpath->registerNamespace('v', 'urn:schemas-microsoft-com:vml');

        // Check if it contains table elements -> Template 3
        $hasTables = $xpath->query('//w:tbl')->length > 0;
        if ($hasTables) {
            $questions = self::parseTemplate3($zip, $xpath, $rels);
            $zip->close();
            return $questions;
        }

        // Scan paragraphs to check for Template 2 markers
        $isTemplate2 = false;
        $paragraphs = $xpath->query('//w:p');
        $checkCount = 0;
        foreach ($paragraphs as $pNode) {
            if ($checkCount > 100) break;
            $text = '';
            $tNodes = $xpath->query('.//w:t', $pNode);
            foreach ($tNodes as $tNode) {
                $text .= $tNode->nodeValue;
            }
            if (preg_match('/\[MARKS\]|\[QUESTION TYPE\]|\[NEGATIVE MARKS\]/i', $text)) {
                $isTemplate2 = true;
                break;
            }
            $checkCount++;
        }

        if ($isTemplate2) {
            $questions = self::parseTemplate2($zip, $xpath, $rels);
        } else {
            $questions = self::parseTemplate1($zip, $xpath, $rels);
        }

        $zip->close();
        return $questions;
    }

    /**
     * Parse Template 1: Paragraph-based with delimiters.
     */
    private static function parseTemplate1(ZipArchive $zip, DOMXPath $xpath, array $rels): array
    {
        $paragraphs = $xpath->query('//w:p');
        $parsedParagraphs = [];

        foreach ($paragraphs as $pNode) {
            $text = '';
            $tNodes = $xpath->query('.//w:t', $pNode);
            foreach ($tNodes as $tNode) {
                $text .= $tNode->nodeValue;
            }

            $imageRelations = [];
            $blips = $xpath->query('.//a:blip', $pNode);
            foreach ($blips as $blip) {
                $rId = $blip->getAttributeNS('http://schemas.openxmlformats.org/officeDocument/2006/relationships', 'embed');
                if ($rId && isset($rels[$rId])) {
                    $imageRelations[] = $rels[$rId];
                }
            }

            $imagedatas = $xpath->query('.//v:imagedata', $pNode);
            foreach ($imagedatas as $imagedata) {
                $rId = $imagedata->getAttributeNS('http://schemas.openxmlformats.org/officeDocument/2006/relationships', 'id');
                if ($rId && isset($rels[$rId])) {
                    $imageRelations[] = $rels[$rId];
                }
            }

            $parsedParagraphs[] = [
                'text' => $text,
                'image_targets' => array_unique($imageRelations)
            ];
        }

        $questions = [];
        $currentQuestion = null;
        $state = 'NONE';

        foreach ($parsedParagraphs as $p) {
            $text = trim($p['text']);
            $targets = $p['image_targets'];

            $storedPaths = [];
            foreach ($targets as $target) {
                $zipPath = 'word/' . ltrim($target, '/');
                if (strpos($target, 'word/') === 0) {
                    $zipPath = $target;
                }

                $imageBytes = $zip->getFromName($zipPath);
                if ($imageBytes) {
                    $ext = pathinfo($target, PATHINFO_EXTENSION) ?: 'png';
                    $storedPath = 'questions/' . Str::uuid() . '.' . $ext;
                    Storage::disk('public')->put($storedPath, $imageBytes);
                    $storedPaths[] = $storedPath;
                }
            }

            if (preg_match('/^[\s\*]*Q[\s\.]*(\d+)\s*\)(.*)$/i', $text, $matches)) {
                if ($currentQuestion) {
                    $questions[] = self::finalizeQuestion($currentQuestion);
                }

                $currentQuestion = [
                    'title'              => trim($matches[2]),
                    'description'        => '',
                    'type'               => 'single_choice_radio',
                    'difficulty'         => 'easy',
                    'marks'              => 1,
                    'explanation'        => '',
                    'options'            => [],
                    'images'             => $storedPaths,
                    'explanation_images' => [],
                ];
                $state = 'QUESTION_BODY';
                continue;
            }

            if (!$currentQuestion) {
                continue;
            }

            if (preg_match('/^\$\$\)(.*)$/', $text)) {
                $state = 'QUESTION_IMAGES';
                if (!empty($storedPaths)) {
                    $currentQuestion['images'] = array_merge($currentQuestion['images'], $storedPaths);
                }
                continue;
            }

            if (preg_match('/^##\)(.*)$/', $text, $matches)) {
                $state = 'EXPLANATION';
                $explanationContent = trim($matches[1]);
                if (strpos($explanationContent, '#*#)') !== false) {
                    $explanationContent = trim(str_replace('#*#)', '', $explanationContent));
                    $state = 'EXPLANATION_IMAGES';
                }
                $currentQuestion['explanation'] = $explanationContent;
                if (!empty($storedPaths)) {
                    $currentQuestion['explanation_images'] = array_merge($currentQuestion['explanation_images'], $storedPaths);
                }
                continue;
            }

            if (preg_match('/^#\*#\)(.*)$/', $text)) {
                $state = 'EXPLANATION_IMAGES';
                if (!empty($storedPaths)) {
                    $currentQuestion['explanation_images'] = array_merge($currentQuestion['explanation_images'], $storedPaths);
                }
                continue;
            }

            if (preg_match('/^\s*(\*)?\s*([a-g])\s*\)(.*)$/i', $text, $matches)) {
                $state = 'OPTIONS';
                $isCorrect = !empty($matches[1]);
                $optionLetter = strtolower($matches[2]);
                $optionText = trim($matches[3]);

                $currentQuestion['options'][] = [
                    'option_text' => $optionText,
                    'is_correct'  => $isCorrect,
                    'letter'      => $optionLetter
                ];
                continue;
            }

            if (preg_match('/^---+\s*$/', $text)) {
                $state = 'NONE';
                continue;
            }

            if ($state === 'QUESTION_BODY') {
                if ($text !== '') {
                    $currentQuestion['title'] .= ($currentQuestion['title'] !== '' ? "\n" : "") . $text;
                }
                if (!empty($storedPaths)) {
                    $currentQuestion['images'] = array_merge($currentQuestion['images'], $storedPaths);
                }
            } elseif ($state === 'QUESTION_IMAGES') {
                if (!empty($storedPaths)) {
                    $currentQuestion['images'] = array_merge($currentQuestion['images'], $storedPaths);
                }
            } elseif ($state === 'EXPLANATION') {
                if ($text !== '') {
                    $currentQuestion['explanation'] .= ($currentQuestion['explanation'] !== '' ? "\n" : "") . $text;
                }
                if (!empty($storedPaths)) {
                    $currentQuestion['explanation_images'] = array_merge($currentQuestion['explanation_images'], $storedPaths);
                }
            } elseif ($state === 'EXPLANATION_IMAGES') {
                if (!empty($storedPaths)) {
                    $currentQuestion['explanation_images'] = array_merge($currentQuestion['explanation_images'], $storedPaths);
                }
            }
        }

        if ($currentQuestion) {
            $questions[] = self::finalizeQuestion($currentQuestion);
        }

        return $questions;
    }

    /**
     * Parse Template 2: Paragraph-based with metadata tags.
     */
    private static function parseTemplate2(ZipArchive $zip, DOMXPath $xpath, array $rels): array
    {
        $paragraphs = $xpath->query('//w:p');
        $parsedParagraphs = [];

        foreach ($paragraphs as $pNode) {
            $text = '';
            $tNodes = $xpath->query('.//w:t', $pNode);
            foreach ($tNodes as $tNode) {
                $text .= $tNode->nodeValue;
            }

            $imageRelations = [];
            $blips = $xpath->query('.//a:blip', $pNode);
            foreach ($blips as $blip) {
                $rId = $blip->getAttributeNS('http://schemas.openxmlformats.org/officeDocument/2006/relationships', 'embed');
                if ($rId && isset($rels[$rId])) {
                    $imageRelations[] = $rels[$rId];
                }
            }

            $imagedatas = $xpath->query('.//v:imagedata', $pNode);
            foreach ($imagedatas as $imagedata) {
                $rId = $imagedata->getAttributeNS('http://schemas.openxmlformats.org/officeDocument/2006/relationships', 'id');
                if ($rId && isset($rels[$rId])) {
                    $imageRelations[] = $rels[$rId];
                }
            }

            $parsedParagraphs[] = [
                'text' => $text,
                'image_targets' => array_unique($imageRelations)
            ];
        }

        $questions = [];
        $currentQuestion = null;
        $state = 'NONE';

        foreach ($parsedParagraphs as $p) {
            $text = trim($p['text']);
            $targets = $p['image_targets'];

            $storedPaths = [];
            foreach ($targets as $target) {
                $zipPath = 'word/' . ltrim($target, '/');
                if (strpos($target, 'word/') === 0) {
                    $zipPath = $target;
                }
                $imageBytes = $zip->getFromName($zipPath);
                if ($imageBytes) {
                    $ext = pathinfo($target, PATHINFO_EXTENSION) ?: 'png';
                    $storedPath = 'questions/' . Str::uuid() . '.' . $ext;
                    Storage::disk('public')->put($storedPath, $imageBytes);
                    $storedPaths[] = $storedPath;
                }
            }

            // Strip and flag QIMAGE / EIMAGE tags
            $hasQImageTag = false;
            $hasEImageTag = false;

            if (preg_match('/\[?QIMAGE(S)?\]?/i', $text)) {
                $hasQImageTag = true;
                $text = trim(preg_replace('/\[?QIMAGE(S)?\]?/i', '', $text));
            }
            if (preg_match('/\[?EIMAGE(S)?\]?/i', $text)) {
                $hasEImageTag = true;
                $text = trim(preg_replace('/\[?EIMAGE(S)?\]?/i', '', $text));
            }

            if (preg_match('/^[\s\*]*Q[\s\.]*(\d+)\s*\)(.*)$/i', $text, $matches)) {
                if ($currentQuestion) {
                    $questions[] = self::finalizeQuestion($currentQuestion);
                }

                $currentQuestion = [
                    'title'              => trim($matches[2]),
                    'description'        => '',
                    'type'               => 'single_choice_radio',
                    'difficulty'         => 'easy',
                    'marks'              => 1,
                    'negative_marks'     => 0.0,
                    'explanation'        => '',
                    'options'            => [],
                    'images'             => $storedPaths,
                    'explanation_images' => [],
                ];
                
                if ($hasQImageTag) {
                    $state = 'QUESTION_IMAGES';
                } else {
                    $state = 'QUESTION_BODY';
                }
                continue;
            }

            if (!$currentQuestion) {
                continue;
            }

            if ($hasQImageTag) {
                $state = 'QUESTION_IMAGES';
                if (!empty($storedPaths)) {
                    $currentQuestion['images'] = array_merge($currentQuestion['images'], $storedPaths);
                }
                if ($text === '') {
                    continue;
                }
            }

            if ($hasEImageTag) {
                $state = 'EXPLANATION_IMAGES';
                if (!empty($storedPaths)) {
                    $currentQuestion['explanation_images'] = array_merge($currentQuestion['explanation_images'], $storedPaths);
                }
                if ($text === '') {
                    continue;
                }
            }

            if (preg_match('/^(\*)?\s*\[(\d+)\]\s*(.*)$/', $text, $matches)) {
                $state = 'OPTIONS';
                $isCorrect = !empty($matches[1]);
                $optionNum = $matches[2];
                $optionText = trim($matches[3]);

                $currentQuestion['options'][] = [
                    'option_text' => $optionText,
                    'is_correct'  => $isCorrect,
                    'letter'      => $optionNum
                ];
                continue;
            }

            if (preg_match('/^\[MARKS\]\s*(.*)$/i', $text, $matches)) {
                $currentQuestion['marks'] = intval(trim($matches[1]));
                continue;
            }

            if (preg_match('/^\[QUESTION TYPE\]\s*(.*)$/i', $text, $matches)) {
                $typeVal = strtoupper(trim($matches[1]));
                if ($typeVal === 'MC') {
                    $currentQuestion['type'] = 'multiple_choice';
                } elseif ($typeVal === 'SC') {
                    $currentQuestion['type'] = 'single_choice_radio';
                } else {
                    $currentQuestion['type'] = strtolower($typeVal);
                }
                continue;
            }

            if (preg_match('/^\[NEGATIVE MARKS\]\s*(.*)$/i', $text, $matches)) {
                $currentQuestion['negative_marks'] = floatval(trim($matches[1]));
                continue;
            }

            if (preg_match('/^\[EXPLANATION\]\s*(.*)$/i', $text, $matches)) {
                $currentQuestion['explanation'] = trim($matches[1]);
                if (!empty($storedPaths)) {
                    $currentQuestion['explanation_images'] = array_merge($currentQuestion['explanation_images'], $storedPaths);
                }
                if ($hasEImageTag) {
                    $state = 'EXPLANATION_IMAGES';
                } else {
                    $state = 'EXPLANATION';
                }
                continue;
            }

            if ($state === 'QUESTION_BODY') {
                if ($text !== '') {
                    $currentQuestion['title'] .= ($currentQuestion['title'] !== '' ? "\n" : "") . $text;
                }
                if (!empty($storedPaths)) {
                    $currentQuestion['images'] = array_merge($currentQuestion['images'], $storedPaths);
                }
            } elseif ($state === 'EXPLANATION') {
                if ($text !== '') {
                    $currentQuestion['explanation'] .= ($currentQuestion['explanation'] !== '' ? "\n" : "") . $text;
                }
                if (!empty($storedPaths)) {
                    $currentQuestion['explanation_images'] = array_merge($currentQuestion['explanation_images'], $storedPaths);
                }
            } elseif ($state === 'QUESTION_IMAGES') {
                if (!empty($storedPaths)) {
                    $currentQuestion['images'] = array_merge($currentQuestion['images'], $storedPaths);
                }
            } elseif ($state === 'EXPLANATION_IMAGES') {
                if (!empty($storedPaths)) {
                    $currentQuestion['explanation_images'] = array_merge($currentQuestion['explanation_images'], $storedPaths);
                }
            }
        }

        if ($currentQuestion) {
            $questions[] = self::finalizeQuestion($currentQuestion);
        }

        return $questions;
    }

    /**
     * Parse Template 3: Table-based.
     */
    private static function parseTemplate3(ZipArchive $zip, DOMXPath $xpath, array $rels): array
    {
        $tblNodes = $xpath->query('//w:tbl');
        $questions = [];

        foreach ($tblNodes as $tblNode) {
            $q = [
                'title'              => '',
                'description'        => '',
                'type'               => 'single_choice_radio',
                'difficulty'         => 'easy',
                'marks'              => 1,
                'negative_marks'     => 0.0,
                'explanation'        => '',
                'options'            => [],
                'images'             => [],
                'explanation_images' => [],
                'metadata'           => [],
            ];

            $trNodes = $xpath->query('.//w:tr', $tblNode);
            foreach ($trNodes as $trNode) {
                $tcNodes = $xpath->query('.//w:tc', $trNode);
                if ($tcNodes->length < 2) {
                    continue;
                }

                $labelText = strtolower(trim(self::parseCellTextOnly($tcNodes->item(0), $xpath)));

                if (strpos($labelText, 'question') !== false) {
                    $cellData = self::parseCell($tcNodes->item(1), $xpath, $rels, $zip);
                    $q['title'] = $cellData['text'];
                    $q['images'] = array_merge($q['images'], $cellData['images']);
                } elseif (strpos($labelText, 'explanation') !== false) {
                    $cellData = self::parseCell($tcNodes->item(1), $xpath, $rels, $zip);
                    $q['explanation'] = $cellData['text'];
                    $q['explanation_images'] = array_merge($q['explanation_images'], $cellData['images']);
                } elseif (strpos($labelText, 'type') !== false) {
                    $typeVal = strtoupper(trim(self::parseCellTextOnly($tcNodes->item(1), $xpath)));
                    if ($typeVal === 'SC') {
                        $q['type'] = 'single_choice_radio';
                    } elseif ($typeVal === 'MC') {
                        $q['type'] = 'multiple_choice';
                    } elseif ($typeVal === 'FB') {
                        $q['type'] = 'fill_in_the_blanks';
                    } else {
                        $q['type'] = strtolower($typeVal);
                    }
                } elseif (strpos($labelText, 'option') !== false) {
                    $cellData = self::parseCell($tcNodes->item(1), $xpath, $rels, $zip);
                    $correctText = '';
                    if ($tcNodes->length >= 3) {
                        $correctText = strtolower(trim(self::parseCellTextOnly($tcNodes->item(2), $xpath)));
                    }
                    $isCorrect = (strpos($correctText, 'correct') !== false && strpos($correctText, 'incorrect') === false);
                    $q['options'][] = [
                        'option_text'  => $cellData['text'],
                        'is_correct'   => $isCorrect,
                        'option_image' => !empty($cellData['images']) ? $cellData['images'][0] : null
                    ];
                } elseif (strpos($labelText, 'marks') !== false) {
                    $marksVal = intval(trim(self::parseCellTextOnly($tcNodes->item(1), $xpath)));
                    $negPercent = 0.0;
                    if ($tcNodes->length >= 3) {
                        $negPercentText = self::parseCellTextOnly($tcNodes->item(2), $xpath);
                        $negPercent = floatval(str_replace('%', '', $negPercentText));
                    }
                    $q['marks'] = $marksVal > 0 ? $marksVal : 1;
                    $q['negative_marks'] = $q['marks'] * ($negPercent / 100.0);
                }
            }

            // Post-process fill in the blanks
            if ($q['type'] === 'fill_in_the_blanks') {
                $title = $q['title'];
                preg_match_all('/##(.*?)##/', $title, $matches);
                if (!empty($matches[1])) {
                    $q['metadata']['blank_answers'] = array_values(array_filter(array_map('trim', $matches[1])));
                    $q['title'] = preg_replace('/##.*?##/', '___', $title);
                }
            }

            // Skip table if no title (e.g. instruction or empty tables)
            if (trim($q['title']) === '') {
                continue;
            }

            $questions[] = self::finalizeQuestion($q);
        }

        return $questions;
    }

    /**
     * Parse cell content (text and images).
     */
    private static function parseCell(\DOMNode $tcNode, DOMXPath $xpath, array $rels, ZipArchive $zip): array
    {
        $paragraphs = $xpath->query('.//w:p', $tcNode);
        $textLines = [];
        $storedPaths = [];

        foreach ($paragraphs as $pNode) {
            $text = '';
            $tNodes = $xpath->query('.//w:t', $pNode);
            foreach ($tNodes as $tNode) {
                $text .= $tNode->nodeValue;
            }
            if (trim($text) !== '') {
                $textLines[] = trim($text);
            }

            $imageRelations = [];
            $blips = $xpath->query('.//a:blip', $pNode);
            foreach ($blips as $blip) {
                $rId = $blip->getAttributeNS('http://schemas.openxmlformats.org/officeDocument/2006/relationships', 'embed');
                if ($rId && isset($rels[$rId])) {
                    $imageRelations[] = $rels[$rId];
                }
            }
            $imagedatas = $xpath->query('.//v:imagedata', $pNode);
            foreach ($imagedatas as $imagedata) {
                $rId = $imagedata->getAttributeNS('http://schemas.openxmlformats.org/officeDocument/2006/relationships', 'id');
                if ($rId && isset($rels[$rId])) {
                    $imageRelations[] = $rels[$rId];
                }
            }

            $imageRelations = array_unique($imageRelations);
            foreach ($imageRelations as $target) {
                $zipPath = 'word/' . ltrim($target, '/');
                if (strpos($target, 'word/') === 0) {
                    $zipPath = $target;
                }
                $imageBytes = $zip->getFromName($zipPath);
                if ($imageBytes) {
                    $ext = pathinfo($target, PATHINFO_EXTENSION) ?: 'png';
                    $storedPath = 'questions/' . Str::uuid() . '.' . $ext;
                    Storage::disk('public')->put($storedPath, $imageBytes);
                    $storedPaths[] = $storedPath;
                }
            }
        }

        return [
            'text'   => implode("\n", $textLines),
            'images' => $storedPaths
        ];
    }

    /**
     * Parse cell text only.
     */
    private static function parseCellTextOnly(\DOMNode $tcNode, DOMXPath $xpath): string
    {
        $text = '';
        $tNodes = $xpath->query('.//w:t', $tcNode);
        foreach ($tNodes as $tNode) {
            $text .= $tNode->nodeValue;
        }
        return trim($text);
    }

    /**
     * Finalize question properties like type and long titles.
     *
     * @param array $q Raw parsed question
     * @return array
     */
    private static function finalizeQuestion(array $q): array
    {
        // 1. Title and Description: map long titles (>255) to description
        $title = $q['title'];
        if (strlen($title) > 255) {
            $q['description'] = $title;
            $q['title'] = mb_substr($title, 0, 250) . '...';
        } else {
            $q['description'] = $title; // default to match system behavior where description is same as title
        }

        // 2. Count options and correct flags to determine question type if choice-based
        if (!isset($q['type']) || in_array($q['type'], ['single_choice_radio', 'multiple_choice', 'single_choice_dropdown'])) {
            $totalOptions = count($q['options']);
            if ($totalOptions === 0) {
                $q['type'] = 'free_text';
            } else {
                $correctCount = 0;
                foreach ($q['options'] as $opt) {
                    if ($opt['is_correct']) {
                        $correctCount++;
                    }
                }

                if ($correctCount > 1) {
                    $q['type'] = 'multiple_choice';
                } else {
                    $q['type'] = 'single_choice_radio';
                }
            }
        }

        return $q;
    }
}
