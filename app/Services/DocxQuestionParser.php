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
            // Load XML safely
            @$dom->loadXML($relsXml, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
            foreach ($dom->getElementsByTagName('Relationship') as $rel) {
                $id = $rel->getAttribute('Id');
                $type = $rel->getAttribute('Type');
                $target = $rel->getAttribute('Target');
                // Only capture image relationships
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

        $paragraphs = $xpath->query('//w:p');
        $parsedParagraphs = [];

        foreach ($paragraphs as $pNode) {
            // Combine all text nodes within this paragraph
            $text = '';
            $tNodes = $xpath->query('.//w:t', $pNode);
            foreach ($tNodes as $tNode) {
                $text .= $tNode->nodeValue;
            }

            // Extract any image relation IDs from modern drawing and legacy VML shape elements
            $imageRelations = [];
            
            // Modern drawings: <a:blip r:embed="rIdX">
            $blips = $xpath->query('.//a:blip', $pNode);
            foreach ($blips as $blip) {
                $rId = $blip->getAttributeNS('http://schemas.openxmlformats.org/officeDocument/2006/relationships', 'embed');
                if ($rId && isset($rels[$rId])) {
                    $imageRelations[] = $rels[$rId];
                }
            }

            // Legacy VML Shapes: <v:imagedata r:id="rIdX">
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

        // 3. Process paragraphs with State Machine
        $questions = [];
        $currentQuestion = null;
        
        // States: 'NONE', 'QUESTION_BODY', 'QUESTION_IMAGES', 'OPTIONS', 'EXPLANATION', 'EXPLANATION_IMAGES'
        $state = 'NONE';

        foreach ($parsedParagraphs as $p) {
            $text = trim($p['text']);
            $targets = $p['image_targets'];

            // Extract image binary content from ZIP and store them
            $storedPaths = [];
            foreach ($targets as $target) {
                // Relationship target path could be relative to document.xml, normalize to 'word/' path prefix
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

            // Detect question start: Q.1) or **Q.1)**
            if (preg_match('/^[\s\*]*Q[\s\.]*(\d+)\s*\)(.*)$/i', $text, $matches)) {
                if ($currentQuestion) {
                    $questions[] = self::finalizeQuestion($currentQuestion);
                }

                $currentQuestion = [
                    'title'              => trim($matches[2]),
                    'description'        => '',
                    'type'               => 'single_choice_radio', // auto-detected later
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
                continue; // Skip any headers/preambles at the start of doc
            }

            // Question Images section marker: $$)
            if (preg_match('/^\$\$\)(.*)$/', $text)) {
                $state = 'QUESTION_IMAGES';
                if (!empty($storedPaths)) {
                    $currentQuestion['images'] = array_merge($currentQuestion['images'], $storedPaths);
                }
                continue;
            }

            // Explanation section marker: ##)
            if (preg_match('/^##\)(.*)$/', $text, $matches)) {
                $state = 'EXPLANATION';
                $currentQuestion['explanation'] = trim($matches[1]);
                if (!empty($storedPaths)) {
                    $currentQuestion['explanation_images'] = array_merge($currentQuestion['explanation_images'], $storedPaths);
                }
                continue;
            }

            // Explanation Images section marker: #*#)
            if (preg_match('/^#\*#\)(.*)$/', $text)) {
                $state = 'EXPLANATION_IMAGES';
                if (!empty($storedPaths)) {
                    $currentQuestion['explanation_images'] = array_merge($currentQuestion['explanation_images'], $storedPaths);
                }
                continue;
            }

            // Option item: a) Option Text or *b) Correct Option Text
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

            // Divider: ---
            if (preg_match('/^---+\s*$/', $text)) {
                $state = 'NONE';
                continue;
            }

            // Fallback: Append text and images to active state elements
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

        // Finalize last question in document if exists
        if ($currentQuestion) {
            $questions[] = self::finalizeQuestion($currentQuestion);
        }

        $zip->close();
        return $questions;
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

        // 2. Count options and correct flags to determine question type
        $totalOptions = count($q['options']);
        if ($totalOptions === 0) {
            $q['type'] = 'free_text'; // Essay if no options are defined
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

        return $q;
    }
}
