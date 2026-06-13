<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Novel;
use App\Models\ReadingHistory;
use App\Services\NovelParserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ChapterController extends Controller
{
    public function create(Novel $novel)
    {
        Gate::authorize('manageChapters', $novel);

        return view('writer.chapters.create', compact('novel'));
    }

    public function store(Request $request, Novel $novel)
    {
        Gate::authorize('manageChapters', $novel);

        $rules = [
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,epub,docx|max:10240', // 10MB max
            'status' => 'required|in:draft,published,scheduled',
            'published_at' => 'nullable|required_if:status,scheduled|date',
        ];

        if ($request->status === 'scheduled') {
            $rules['published_at'] .= '|after:now';
        }

        $request->validate($rules);

        $slug = Str::slug($request->title);
        $originalSlug = $slug;
        $count = 1;
        while (Chapter::where('novel_id', $novel->id)->where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        $chapter = new Chapter;
        $chapter->novel_id = $novel->id;
        $chapter->title = $request->title;
        $chapter->slug = $slug;
        $chapter->content = $request->content;
        $chapter->status = $request->status;
        $chapter->published_at = $request->status === 'scheduled' ? $request->published_at : ($request->status === 'published' ? now() : null);

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('chapters', 'public');
            $chapter->file_path = $path;
        }

        $chapter->save();

        return redirect()->route('dashboard', ['tab' => 'library'])->with('success', 'Chapter added successfully!');
    }

    public function bulkStore(Request $request, Novel $novel, NovelParserService $parser)
    {
        Gate::authorize('manageChapters', $novel);

        $request->validate([
            'file' => 'required|file|mimes:epub,docx,pdf,zip|max:51200', // 50MB max
        ]);

        try {
            $file = $request->file('file');
            $path = $file->path();
            $chapters = $parser->parse($path, $file->getClientOriginalExtension());

            if (empty($chapters)) {
                return back()->with('error', 'No chapters found in the file.');
            }

            foreach ($chapters as $index => $data) {
                $slug = Str::slug($data['title']);

                // Pastikan slug unik dalam novel ini
                $originalSlug = $slug;
                $count = 1;
                while (Chapter::where('novel_id', $novel->id)->where('slug', $slug)->exists()) {
                    $slug = $originalSlug.'-'.$count++;
                }

                Chapter::create([
                    'novel_id' => $novel->id,
                    'title' => $data['title'],
                    'slug' => $slug,
                    'content' => $data['content'],
                ]);
            }

            return redirect()->route('dashboard', ['tab' => 'library'])
                ->with('success', count($chapters).' chapters successfully imported!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to process file: '.$e->getMessage());
        }
    }

    public function parseDocument(Request $request, Novel $novel, NovelParserService $parser)
    {
        Gate::authorize('manageChapters', $novel);

        $request->validate([
            'file' => 'required|file|mimes:epub,docx,pdf|max:51200',
        ]);

        try {
            $file = $request->file('file');
            $path = $file->path();
            $chapters = $parser->parse($path, $file->getClientOriginalExtension());

            if (empty($chapters)) {
                return response()->json(['error' => 'Tidak ada chapter yang ditemukan dalam file tersebut.'], 422);
            }

            return response()->json([
                'chapters' => $chapters
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal memproses file: '.$e->getMessage()], 500);
        }
    }

    public function storeBulkChapter(Request $request, Novel $novel)
    {
        Gate::authorize('manageChapters', $novel);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $slug = Str::slug($request->title);

        // Pastikan slug unik dalam novel ini
        $originalSlug = $slug;
        $count = 1;
        while (Chapter::where('novel_id', $novel->id)->where('slug', $slug)->exists()) {
            $slug = $originalSlug.'-'.$count++;
        }

        $chapter = Chapter::create([
            'novel_id' => $novel->id,
            'title' => $request->title,
            'slug' => $slug,
            'content' => $request->content,
            'status' => 'published',
            'published_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'chapter' => $chapter
        ]);
    }

    public function show(Novel $novel, $chapterSlug)
    {
        $chapter = Chapter::where('novel_id', $novel->id)
            ->where('slug', $chapterSlug)
            ->firstOrFail();

        // Check if user is author or admin to see non-published chapters
        $isAuthorOrAdmin = Auth::check() && (Auth::user()->role === 'admin' || $novel->author_id === Auth::id());

        if (! $isAuthorOrAdmin && ($chapter->status !== 'published' || ($chapter->published_at && $chapter->published_at->isFuture()))) {
            abort(404);
        }

        $chapter->load([
            'comments' => function ($query) {
                $query->whereNull('parent_id')
                    ->with([
                        'user',
                        'likes',
                        'dislikes',
                        'replies' => fn ($q) => $q->with(['user', 'likes', 'dislikes']),
                    ])
                    ->latest();
            },
        ]);
        $commentsCount = $chapter->comments()->count();

        if (Auth::check()) {
            ReadingHistory::updateOrCreate(
                ['user_id' => Auth::id(), 'novel_id' => $novel->id],
                ['chapter_id' => $chapter->id]
            );
        }

        $previousChapter = $chapter->previous(! $isAuthorOrAdmin);
        $nextChapter = $chapter->next(! $isAuthorOrAdmin);

        $allChapters = $novel->chapters()
            ->when(! $isAuthorOrAdmin, function ($query) {
                return $query->where('status', 'published')
                    ->where(function ($q) {
                        $q->whereNull('published_at')
                            ->orWhere('published_at', '<=', now());
                    });
            })
            ->orderBy('created_at', 'asc') // or however they are ordered
            ->get(['title', 'slug']);

        $protectContent = ! $isAuthorOrAdmin;
        $chapterBodyHtml = $this->formatChapterBodyForDisplay(
            $chapter->content,
            $protectContent,
        );

        if (request()->ajax()) {
            return response()->json([
                'novel' => [
                    'title' => $novel->title,
                    'slug' => $novel->slug,
                ],
                'chapter' => [
                    'id' => $chapter->id,
                    'title' => $chapter->title,
                    'slug' => $chapter->slug,
                    'content' => $chapterBodyHtml,
                    'prev_chapter_slug' => $previousChapter ? $previousChapter->slug : null,
                    'next_chapter_slug' => $nextChapter ? $nextChapter->slug : null,
                    'comments_count' => $chapter->comments->count(),
                ],
                'all_chapters' => $allChapters,
            ]);
        }

        return view('chapters.show', compact(
            'novel',
            'chapter',
            'previousChapter',
            'nextChapter',
            'allChapters',
            'protectContent',
            'chapterBodyHtml',
            'commentsCount',
        ));
    }

    /**
     * Sisipkan watermark tipis antar blok paragraf (pisah baris kosong). Tanpa watermark jika penulis/admin.
     */
    private function formatChapterBodyForDisplay(?string $content, bool $withWatermark): string
    {
        $raw = $content ?? '';
        
        // Bersihkan konten dari spasi berlebih
        $normalized = str_replace(["\r\n", "\r"], "\n", $raw);
        $normalized = trim($normalized);

        // Pecah berdasarkan paragraf (double newline)
        $paras = preg_split("/\n\s*\n/", $normalized, -1, PREG_SPLIT_NO_EMPTY);
        
        if (empty($paras)) {
            return '<p>' . nl2br(e($normalized)) . '</p>';
        }

        $parts = [];
        foreach ($paras as $i => $p) {
            $parts[] = '<p class="mb-5">' . nl2br(e(trim($p))) . '</p>';
        }

        return implode('', $parts);
    }

    public function edit(Novel $novel, Chapter $chapter)
    {
        Gate::authorize('update', $chapter);

        return view('writer.chapters.edit', compact('novel', 'chapter'));
    }

    public function update(Request $request, Novel $novel, Chapter $chapter)
    {
        Gate::authorize('update', $chapter);

        $rules = [
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,epub,docx|max:10240',
            'status' => 'required|in:draft,published,scheduled',
            'published_at' => 'nullable|required_if:status,scheduled|date',
        ];

        // Hanya validasi after:now jika status diubah ke scheduled 
        // ATAU jika waktu penjadwalan diubah dari nilai sebelumnya
        if ($request->status === 'scheduled') {
            $currentPublishedAt = $chapter->published_at ? $chapter->published_at->format('Y-m-d\TH:i') : null;
            if ($chapter->status !== 'scheduled' || $request->published_at !== $currentPublishedAt) {
                $rules['published_at'] .= '|after:now';
            }
        }

        $request->validate($rules);

        // Update slug jika judul berubah
        if ($chapter->title !== $request->title) {
            $slug = Str::slug($request->title);
            $originalSlug = $slug;
            $count = 1;
            while (Chapter::where('novel_id', $novel->id)
                ->where('slug', $slug)
                ->where('id', '!=', $chapter->id)
                ->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }
            $chapter->slug = $slug;
        }

        $chapter->title = $request->title;
        $chapter->content = $request->content;
        $chapter->status = $request->status;

        if ($request->status === 'scheduled') {
            $chapter->published_at = $request->published_at;
        } elseif ($request->status === 'published') {
            // Jika sebelumnya draft atau dijadwalkan di masa depan, set ke sekarang
            if (!$chapter->published_at || $chapter->published_at->isFuture()) {
                $chapter->published_at = now();
            }
        } elseif ($request->status === 'draft') {
            $chapter->published_at = null;
        }

        if ($request->hasFile('file')) {
            if ($chapter->file_path) {
                Storage::disk('public')->delete($chapter->file_path);
            }
            $path = $request->file('file')->store('chapters', 'public');
            $chapter->file_path = $path;
        }

        $chapter->save();

        return redirect()->route('dashboard', ['tab' => 'library'])->with('success', 'Chapter updated successfully!');
    }

    public function destroy(Novel $novel, Chapter $chapter)
    {
        Gate::authorize('delete', $chapter);

        if ($chapter->file_path) {
            Storage::disk('public')->delete($chapter->file_path);
        }

        $chapter->delete();

        return redirect()->route('dashboard', ['tab' => 'library'])->with('success', 'Chapter deleted successfully!');
    }
}
