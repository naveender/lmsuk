<?php

$tempFile = __DIR__ . '/sample.docx';
$zip = new ZipArchive();
if ($zip->open($tempFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
    $documentXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"
            xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"
            xmlns:a="http://schemas.openxmlformats.org/drawingml/2006/main">
    <w:body>
        <w:p>
            <w:r><w:t>**Q.1)** The concept of laissez-faire economy refers to...</w:t></w:r>
        </w:p>
        <w:p>
            <w:r><w:t>$$) Question Multiple Images</w:t></w:r>
            <w:r>
                <w:drawing>
                    <a:blip r:embed="rIdQ1"/>
                </w:drawing>
            </w:r>
        </w:p>
        <w:p>
            <w:r><w:t>a) Government control over the allocation of all factors of production.</w:t></w:r>
        </w:p>
        <w:p>
            <w:r><w:t>*b) Non-interference by the government and free functioning of demand and supply forces in the market.</w:t></w:r>
        </w:p>
        <w:p>
            <w:r><w:t>c) The customers take all the decisions regarding production of all the commodities.</w:t></w:r>
        </w:p>
        <w:p>
            <w:r><w:t>d) Private sector takes all the decisions for price determination of various commodities produced.</w:t></w:r>
        </w:p>
        <w:p>
            <w:r><w:t>##) Explanation of the question</w:t></w:r>
        </w:p>
        <w:p>
            <w:r><w:t>#*#) Explanation Multiple Images Original in DOCX</w:t></w:r>
            <w:r>
                <w:drawing>
                    <a:blip r:embed="rIdE1"/>
                </w:drawing>
            </w:r>
        </w:p>
        <w:p>
            <w:r><w:t>---</w:t></w:r>
        </w:p>
        <w:p>
            <w:r><w:t>**Q.2)** Which of the following is likely to be most inflationary in its impact?</w:t></w:r>
        </w:p>
        <w:p>
            <w:r><w:t>a) Repayment of public debt</w:t></w:r>
        </w:p>
        <w:p>
            <w:r><w:t>b) Borrowing from the public to finance a budget deficit</w:t></w:r>
        </w:p>
        <w:p>
            <w:r><w:t>c) Borrowings from banks to finance a budget deficit</w:t></w:r>
        </w:p>
        <w:p>
            <w:r><w:t>*d) Creating new money to finance a budget deficit</w:t></w:r>
        </w:p>
        <w:p>
            <w:r><w:t>---</w:t></w:r>
        </w:p>
    </w:body>
</w:document>';

    $relsXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
    <Relationship Id="rIdQ1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="media/image1.png"/>
    <Relationship Id="rIdE1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/image" Target="media/image2.png"/>
</Relationships>';

    $pngBytes = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==');

    $zip->addFromString('word/document.xml', $documentXml);
    $zip->addFromString('word/_rels/document.xml.rels', $relsXml);
    $zip->addFromString('word/media/image1.png', $pngBytes);
    $zip->addFromString('word/media/image2.png', $pngBytes);
    $zip->close();
    echo "Sample docx generated successfully at " . $tempFile;
} else {
    echo "Failed to open zip archive.";
}
