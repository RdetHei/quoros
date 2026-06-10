<?php

namespace Tests\Feature;

use App\Models\Chapter;
use App\Models\Novel;
use App\Models\User;
use App\Services\EpubParserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Tests\TestCase;

class EpubBulkUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_epub_bulk_upload_extracts_chapters_correctly()
    {
        Storage::fake('public');

        $user = User::factory()->create(['role' => 'writer']);
        $novel = Novel::factory()->create(['author_id' => $user->id]);

        // 1. Create a mock EPUB file
        $tempFile = tempnam(sys_get_temp_dir(), 'test') . '.epub';
        $zip = new ZipArchive();
        $zip->open($tempFile, ZipArchive::CREATE);

        // container.xml
        $zip->addFromString('META-INF/container.xml', '<?xml version="1.0"?>
            <container version="1.0" xmlns="urn:oasis:names:tc:opendocument:xmlns:container">
                <rootfiles>
                    <rootfile full-path="OEBPS/content.opf" media-type="application/oebps-package+xml"/>
                </rootfiles>
            </container>');

        // content.opf
        $zip->addFromString('OEBPS/content.opf', '<?xml version="1.0" encoding="UTF-8"?>
            <package xmlns="http://www.idpf.org/2007/opf" version="2.0">
                <metadata xmlns:dc="http://purl.org/dc/elements/1.1/">
                    <dc:title>Test EPUB</dc:title>
                    <dc:creator>Test Author</dc:creator>
                </metadata>
                <manifest>
                    <item id="chapter1" href="chap1.xhtml" media-type="application/xhtml+xml"/>
                    <item id="chapter2" href="chap2.xhtml" media-type="application/xhtml+xml"/>
                    <item id="ncx" href="toc.ncx" media-type="application/x-dtbncx+xml"/>
                </manifest>
                <spine toc="ncx">
                    <itemref idref="chapter1"/>
                    <itemref idref="chapter2"/>
                </spine>
            </package>');

        // toc.ncx
        $zip->addFromString('OEBPS/toc.ncx', '<?xml version="1.0" encoding="UTF-8"?>
            <ncx xmlns="http://www.daisy.org/z3986/2005/ncx/" version="2005-1">
                <navMap>
                    <navPoint id="np1" playOrder="1">
                        <navLabel><text>Introduction</text></navLabel>
                        <content src="chap1.xhtml"/>
                    </navPoint>
                    <navPoint id="np2" playOrder="2">
                        <navLabel><text>The Journey Begins</text></navLabel>
                        <content src="chap2.xhtml"/>
                    </navPoint>
                </navMap>
            </ncx>');

        // Chapters
        $zip->addFromString('OEBPS/chap1.xhtml', '<html><body><h1>Introduction</h1><p>Content of chapter 1.</p></body></html>');
        $zip->addFromString('OEBPS/chap2.xhtml', '<html><body><h2>The Journey Begins</h2><p>Content of chapter 2.</p></body></html>');

        $zip->close();

        $uploadedFile = new UploadedFile(
            $tempFile,
            'test.epub',
            'application/epub+zip',
            null,
            true
        );

        // 2. Act
        $response = $this->actingAs($user)
            ->post(route('writer.novels.chapters.bulk-upload', $novel), [
                'file' => $uploadedFile
            ]);

        // 3. Assert
        $response->assertRedirect();
        
        $count = Chapter::where('novel_id', $novel->id)->count();
        $this->assertEquals(2, $count);
        
        $this->assertDatabaseHas('chapters', [
            'novel_id' => $novel->id,
            'title' => 'Introduction',
        ]);
        
        $this->assertDatabaseHas('chapters', [
            'novel_id' => $novel->id,
            'title' => 'The Journey Begins',
        ]);

        if (file_exists($tempFile)) {
            unlink($tempFile);
        }
    }
}
