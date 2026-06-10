<?php

namespace Tests\Feature;

use App\Models\Chapter;
use App\Models\Novel;
use App\Models\User;
use App\Services\DocxParserService;
use App\Services\NovelParserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Tests\TestCase;

class DocxBulkUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_docx_bulk_upload_with_headings_creates_chapters()
    {
        Storage::fake('public');

        // 1. Setup User and Novel
        $user = User::factory()->create(['role' => 'writer']);
        $novel = Novel::factory()->create(['author_id' => $user->id]);

        // 2. Create a temporary DOCX file with 3 Headings
        $phpWord = new PhpWord();
        $phpWord->addTitleStyle(1, ['bold' => true, 'size' => 16], ['style' => 'Heading 1']);
        $section = $phpWord->addSection();
        
        // Metadata
        $section->addText('Title: Test Novel');
        $section->addText('Description: This is a test');
        
        // Chapter 1
        $section->addTitle('Chapter One', 1);
        $section->addText('Content for chapter one.');
        
        // Chapter 2
        $section->addTitle('Chapter Two', 1);
        $section->addText('Content for chapter two.');
        
        // Chapter 3
        $section->addTitle('Chapter Three', 1);
        $section->addText('Content for chapter three.');

        $tempFile = tempnam(sys_get_temp_dir(), 'test') . '.docx';
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempFile);

        $uploadedFile = new UploadedFile(
            $tempFile,
            'test.docx',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            null,
            true
        );

        // 3. Act: Call the bulk store endpoint
        $response = $this->actingAs($user)
            ->post(route('writer.novels.chapters.bulk-upload', $novel), [
                'file' => $uploadedFile
            ]);

        // 4. Assert
        $response->assertRedirect();
        
        $chapters = Chapter::where('novel_id', $novel->id)->get(['title', 'content']);
        $this->assertEquals(3, $chapters->count());
        
        $this->assertDatabaseHas('chapters', [
            'novel_id' => $novel->id,
            'title' => 'Chapter One',
            'slug' => 'chapter-one',
        ]);
        
        $this->assertDatabaseHas('chapters', [
            'novel_id' => $novel->id,
            'title' => 'Chapter Two',
            'slug' => 'chapter-two',
        ]);
        
        $this->assertDatabaseHas('chapters', [
            'novel_id' => $novel->id,
            'title' => 'Chapter Three',
            'slug' => 'chapter-three',
        ]);

        // Clean up
        if (file_exists($tempFile)) {
            unlink($tempFile);
        }
    }

    public function test_docx_bulk_upload_fallback_to_keywords_if_no_headings()
    {
        Storage::fake('public');

        $user = User::factory()->create(['role' => 'writer']);
        $novel = Novel::factory()->create(['author_id' => $user->id]);

        // Create a DOCX without Heading 1, but with "Bab" keywords
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        
        $section->addText('Bab 1: Awal Mula');
        $section->addText('Konten bab satu.');
        $section->addText('Bab 2: Perjalanan');
        $section->addText('Konten bab dua.');

        $tempFile = tempnam(sys_get_temp_dir(), 'test_fallback') . '.docx';
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempFile);

        $uploadedFile = new UploadedFile(
            $tempFile,
            'test_fallback.docx',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            null,
            true
        );

        $response = $this->actingAs($user)
            ->post(route('writer.novels.chapters.bulk-upload', $novel), [
                'file' => $uploadedFile
            ]);

        $response->assertRedirect();
        $this->assertEquals(2, Chapter::where('novel_id', $novel->id)->count());
        
        $this->assertDatabaseHas('chapters', [
            'novel_id' => $novel->id,
            'title' => 'Bab 1: Awal Mula',
        ]);

        if (file_exists($tempFile)) {
            unlink($tempFile);
        }
    }
}
