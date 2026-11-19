<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Novel;
use App\Models\ReadingHistory;
use App\Services\CloudinaryService;
use App\Services\RecommendationService;
use App\Services\NovelParserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class ChapterController extends Controller
{
    public function create(Novel $novel)
    {
        Gate::authorize('manageChapters', $novel);

        return view('writer.chapters.create', compact('novel'));
    }

    public function bulkCreate(Novel $novel)
    {
        Gate::authorize('manageChapters', $novel);

        $existingChapters = $novel->chapters()->orderByRaw('COALESCE(NULLIF(`order`, 0), id) asc')->get(['id', 'title', 'order', 'status']);

        return view('writer.chapters.bulk', compact('novel', 'existingChapters'));
    }

    public function store(Request $request, Novel $novel, CloudinaryService $cloudinary)
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
            $chapter->file_path = $cloudinary->uploadChapterFile($request->file('file'))['url'];
        }

        $chapter->save();

        return redirect()->route('writer.chapters', ['novel_id' => $novel->id])->with('success', 'Chapter added successfully!');
    }

    public function bulkStore(Request $request, Novel $novel, NovelParserService $parser)
    {
        Gate::authorize('manageChapters', $novel);

        $request->validate([
            'file' => 'required|file|mimes:epub,docx,doc,pdf|max:51200',
        ]);

        try {
            $file = $request->file('file');
            $chapters = $parser->parse($file->path(), $file->getClientOriginalExtension());

            if (empty($chapters)) {
                return back()->with('error', 'Tidak ada bagian terstruktur yang ditemukan. Gunakan Preview & Edit sebelum menyimpan.');
            }

            $data = ['chapters' => $chapters];

            if ($request->wantsJson()) {
                return response()->json($data);
            }

            return back()->with('info', 'Gunakan Preview & Edit untuk meninjau hasil sebelum menyimpan.')
                ->with('detected_chapters', $chapters);
        } catch (\Throwable $e) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Gagal memproses file: '.$e->getMessage()], 422);
            }

            return back()->with('error', 'Gagal memproses file: '.$e->getMessage());
        }
    }

    public function parseDocument(Request $request, Novel $novel, NovelParserService $parser)
    {
        Gate::authorize('manageChapters', $novel);

        $request->validate([
            'file' => 'required|file|mimes:epub,docx,doc,pdf|max:51200',
        ]);

        try {
            $file = $request->file('file');
            $chapters = $parser->parse($file->path(), $file->getClientOriginalExtension());

            if (empty($chapters)) {
                return response()->json(['error' => 'Tidak ada chapter yang ditemukan dalam file tersebut.'], 422);
            }

            return response()->json([
                'chapters' => $chapters,
            ]);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Gagal memproses file: '.$e->getMessage()], 422);
        }
    }

    public function storeBulkChapter(Request $request, Novel $novel)
    {
        Gate::authorize('manageChapters', $novel);

        if ($request->has('chapters')) {
            $validated = $request->validate([
                'chapters' => 'required|array|min:1|max:500',
                'chapters.*.title' => 'required|string|max:255',
                'chapters.*.content' => 'nullable|string',
            ]);

            $created = DB::transaction(function () use ($novel, $validated) {
                $nextOrder = (int) $novel->chapters()->max('order');
                $created = [];

                foreach ($validated['chapters'] as $data) {
                    $title = trim($data['title']);
                    $slug = $this->uniqueChapterSlug($novel, $title);

                    $created[] = Chapter::create([
                        'novel_id' => $novel->id,
                        'title' => $title,
                        'slug' => $slug,
                        'content' => (string) ($data['content'] ?? ''),
                        'status' => 'published',
                        'published_at' => now(),
                        'order' => ++$nextOrder,
                    ]);
                }

                return $created;
            });

            return response()->json(['success' => true, 'chapters' => $created], 201);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
        ]);

        $chapter = DB::transaction(function () use ($novel, $validated) {
            $slug = $this->uniqueChapterSlug($novel, $validated['title']);

            return Chapter::create([
                'novel_id' => $novel->id,
                'title' => trim($validated['title']),
                'slug' => $slug,
                'content' => (string) ($validated['content'] ?? ''),
                'status' => 'published',
                'published_at' => now(),
                'order' => ((int) $novel->chapters()->max('order')) + 1,
            ]);
        });

        return response()->json([
            'success' => true,
            'chapter' => $chapter,
        ], 201);
    }

    private function uniqueChapterSlug(Novel $novel, string $title): string
    {
        $slug = Str::slug($title) ?: 'chapter';
        $originalSlug = $slug;
        $counter = 1;

        while (Chapter::where('novel_id', $novel->id)->where('slug', $slug)->exists()) {
            $slug = $originalSlug.'-'.$counter++;
        }

        return $slug;
    }

    public function show(Novel $novel, $chapter)
    {
        // InfinityFree tidak punya cron job, jadi chapter terjadwal yang sudah
        // waktunya diterbitkan saat diakses.
        Chapter::publishScheduledDue($novel->id);

        $identifier = $chapter;
        $query = Chapter::where('novel_id', $novel->id);

        if (is_numeric($identifier)) {
            // Identifier numerik bisa berupa nomor urut (order) atau id chapter.
            // Chapter dengan order 0 memakai id sebagai identifier kanonik.
            $chapter = (clone $query)->where('order', (int) $identifier)->first()
                ?? (clone $query)->where('id', (int) $identifier)->first()
                ?? abort(404);
        } else {
            $chapter = $query->where('slug', $identifier)->firstOrFail();
        }

        if (!is_numeric($identifier) && $chapter->order > 0) {
            return redirect()->route('chapters.show', [$novel->slug, $chapter->order], 301);
        }

        // Check if user is author or admin to see non-published chapters
        $isAuthorOrAdmin = Auth::check() && (Auth::user()->role === 'admin' || $novel->author_id === Auth::id());

        if (! $isAuthorOrAdmin && ($chapter->status !== 'published' || ($chapter->published_at && $chapter->published_at->isFuture()))) {
            abort(404);
        }

        $sort = request('sort') === 'best' ? 'best' : 'new';

        $chapter->load([
            'comments' => function ($query) use ($sort) {
                $query->whereNull('parent_id')
                    ->with([
                        'user',
                        'reactions',
                        'replies' => fn ($q) => $q->with(['user', 'reactions'])->withCount(['likes', 'dislikes']),
                    ])
                    ->withCount(['likes', 'dislikes'])
                    ->when(
                        $sort === 'best',
                        fn ($q) => $q->orderByRaw('(likes_count - dislikes_count) desc')->latest(),
                        fn ($q) => $q->latest(),
                    );
            },
        ]);
        $commentsCount = $chapter->comments()->count();

        if (Auth::check()) {
            ReadingHistory::updateOrCreate(
                ['user_id' => Auth::id(), 'novel_id' => $novel->id],
                ['chapter_id' => $chapter->id]
            );
            app(RecommendationService::class)->forgetForUser(Auth::id());
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
            ->orderByRaw('COALESCE(NULLIF(`order`, 0), id) asc')
            ->get(['id', 'title', 'slug', 'order']);

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
                    'order' => $chapter->order,
                    'content' => $chapterBodyHtml,
                    'prev_chapter_order' => $previousChapter ? ($previousChapter->order ?: null) : null,
                    'next_chapter_order' => $nextChapter ? ($nextChapter->order ?: null) : null,
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
            'sort',
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

    public function update(Request $request, Novel $novel, Chapter $chapter, CloudinaryService $cloudinary)
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
            $cloudinary->deleteChapterFile($chapter->file_path);
            $chapter->file_path = $cloudinary->uploadChapterFile($request->file('file'))['url'];
        }

        $chapter->save();

        return redirect()->route('writer.chapters', ['novel_id' => $novel->id])->with('success', 'Chapter updated successfully!');
    }

    public function reorder(Request $request, Novel $novel)
    {
        Gate::authorize('manageChapters', $novel);

        $validated = $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:chapters,id',
        ]);

        foreach ($validated['order'] as $index => $chapterId) {
            Chapter::where('id', $chapterId)
                ->where('novel_id', $novel->id)
                ->update(['order' => $index + 1]);
        }

        return back()->with('success', 'Urutan chapter berhasil diperbarui.');
    }

    public function destroy(Novel $novel, Chapter $chapter, CloudinaryService $cloudinary)
    {
        Gate::authorize('delete', $chapter);

        $cloudinary->deleteChapterFile($chapter->file_path);

        // Cascade di level aplikasi (lihat Chapter::booted) → bungkus transaksi.
        DB::transaction(fn () => $chapter->delete());

        return redirect()->route('writer.chapters', ['novel_id' => $novel->id])->with('success', 'Chapter deleted successfully!');
    }
}
