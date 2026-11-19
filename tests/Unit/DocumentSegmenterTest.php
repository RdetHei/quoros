<?php

namespace Tests\Unit;

use App\Services\DocumentSegmenter;
use PHPUnit\Framework\TestCase;

class DocumentSegmenterTest extends TestCase
{
    public function test_it_uses_actual_headings_and_keeps_intro_and_scene_markers(): void
    {
        $segmenter = new DocumentSegmenter;
        $titles = [
            'Kekalahan Pertama: Sungguh, Tak Ada Maksud Terselubung',
            'Selingan: Kenangan Paling Berharga',
            'Kekalahan Kedua: Tersentak oleh Beban di Pundak',
            'Selingan: Percayalah, Sebenarnya Aku Laris Manis',
            'Kekalahan Ketiga: Rapsodi Kegilaan Merah',
            'Selingan: Saat-nya Beres-Beres',
            'Kekalahan Keempat: Siapa Sebenarnya Musuh Para Perempuan?',
            'Epilog: Anak Tangga Menuju Kedewasaan',
        ];

        $blocks = [
            ['text' => 'Daftar Isi', 'source_page' => 5],
        ];
        foreach (array_slice($titles, 0, 4) as $index => $title) {
            $blocks[] = ['text' => $title.' '.(20 + $index), 'source_page' => 5];
        }
        foreach (array_slice($titles, 4) as $index => $title) {
            $blocks[] = ['text' => $title.' '.(80 + $index), 'source_page' => 6];
        }
        $blocks[] = ['text' => 'Opening narrative paragraph one.', 'source_page' => 7];
        $blocks[] = ['text' => 'Opening narrative paragraph two.', 'source_page' => 10];

        foreach ($titles as $index => $title) {
            $blocks[] = ['text' => $title, 'source_page' => 11 + $index * 20];
            $blocks[] = ['text' => 'Chapter body paragraph. ◇ scene change. [ Selesai ]', 'source_page' => 12 + $index * 20];
        }

        $chapters = $segmenter->segment($blocks);

        self::assertSame($titles, array_column($chapters, 'title'));
        self::assertStringContainsString('Opening narrative paragraph one.', $chapters[0]['content']);
        self::assertStringContainsString('Opening narrative paragraph two.', $chapters[0]['content']);
        self::assertStringContainsString('◇ scene change. [ Selesai ]', $chapters[0]['content']);
        self::assertSame('intermission', $chapters[1]['chapter_type']);
        self::assertSame('epilogue', $chapters[7]['chapter_type']);
        self::assertSame(7, $chapters[0]['source_page_start']);
        self::assertSame(11, $chapters[0]['heading_page']);
    }

    public function test_it_preserves_unstructured_documents_as_one_unit_without_inventing_a_title(): void
    {
        $chapters = (new DocumentSegmenter)->segment([
            ['text' => 'A paragraph that is not a chapter heading.'],
            ['text' => 'A second paragraph stays in the same section.'],
            ['text' => '◇'],
        ]);

        self::assertCount(1, $chapters);
        self::assertSame('', $chapters[0]['title']);
        self::assertSame('unsegmented', $chapters[0]['detection_method']);
        self::assertStringContainsString('A paragraph that is not a chapter heading.', $chapters[0]['content']);
        self::assertStringContainsString('A second paragraph stays in the same section.', $chapters[0]['content']);
        self::assertStringContainsString('◇', $chapters[0]['content']);
    }

    public function test_only_top_level_docx_headings_split_sections(): void
    {
        $chapters = (new DocumentSegmenter)->segment([
            ['text' => 'The Beginning', 'heading_level' => 1],
            ['text' => 'First paragraph.'],
            ['text' => 'A subheading', 'heading_level' => 2],
            ['text' => 'Second paragraph.'],
            ['text' => 'The Journey', 'heading_level' => 1],
            ['text' => 'Third paragraph.'],
        ]);

        self::assertSame(['The Beginning', 'The Journey'], array_column($chapters, 'title'));
        self::assertStringContainsString('A subheading', $chapters[0]['content']);
        self::assertStringContainsString('Second paragraph.', $chapters[0]['content']);
    }
}