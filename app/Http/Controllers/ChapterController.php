<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Novel;
use App\Models\ReadingHistory;
use App\Services\EpubParserService;
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

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,epub,docx|max:10240', // 10MB max
            'status' => 'required|in:draft,published,scheduled',
            'published_at' => 'nullable|required_if:status,scheduled|date|after:now',
        ]);

        $slug = Str::slug($request->title);

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

        return redirect()->route('novels.show', $novel->slug)->with('success', 'Chapter added successfully!');
    }

    public function bulkStore(Request $request, Novel $novel, EpubParserService $parser)
    {
        Gate::authorize('manageChapters', $novel);

        $request->validate([
            'epub_file' => 'required|file|mimes:epub|max:51200', // 50MB max for EPUB
        ]);

        try {
            $path = $request->file('epub_file')->path();
            $chapters = $parser->parse($path);

            if (empty($chapters)) {
                return back()->with('error', 'Tidak ada chapter yang ditemukan dalam file EPUB tersebut.');
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

            return redirect()->route('novels.show', $novel->slug)
                ->with('success', count($chapters).' chapter berhasil diimpor dari EPUB!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses EPUB: '.$e->getMessage());
        }
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

        $chapter->load('comments.user');

        if (Auth::check()) {
            ReadingHistory::updateOrCreate(
                ['user_id' => Auth::id(), 'novel_id' => $novel->id],
                ['chapter_id' => $chapter->id]
            );
        }

        $previousChapter = $chapter->previous(! $isAuthorOrAdmin);
        $nextChapter = $chapter->next(! $isAuthorOrAdmin);

        $protectContent = ! $isAuthorOrAdmin;
        $chapterBodyHtml = $this->formatChapterBodyForDisplay(
            $chapter->content,
            $this->readerWatermarkLabel(),
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
                    'next_chapter_slug' => $nextChapter ? $nextChapter->slug : null,
                    'comments_count' => $chapter->comments->count(),
                ],
            ]);
        }

        return view('chapters.show', compact(
            'novel',
            'chapter',
            'previousChapter',
            'nextChapter',
            'protectContent',
            'chapterBodyHtml',
        ));
    }

    private function readerWatermarkLabel(): string
    {
        if (Auth::check()) {
            return Auth::user()->name.' · '.config('app.name', 'Quoros');
        }

        return (string) config('app.name', 'Quoros');
    }

    /**
     * Sisipkan watermark tipis antar blok paragraf (pisah baris kosong). Tanpa watermark jika penulis/admin.
     */
    private function formatChapterBodyForDisplay(?string $content, string $watermarkLabel, bool $withWatermark): string
    {
        $raw = $content ?? '';
        if (! $withWatermark) {
            return nl2br(e($raw));
        }

        $normalized = str_replace(["\r\n", "\r"], "\n", $raw);
        $paras = preg_split("/\n\s*\n/", $normalized, -1, PREG_SPLIT_NO_EMPTY);
        $wm = '<span class="chapter-wm block text-center text-[10px] sm:text-xs my-5 text-slate-400/25 dark:text-slate-500/30 select-none pointer-events-none tracking-widest font-semibold" aria-hidden="true">'.e($watermarkLabel).'</span>';

        if ($paras === false || count($paras) < 2) {
            return nl2br(e($normalized)).$wm;
        }

        $parts = [];
        foreach ($paras as $i => $p) {
            $parts[] = nl2br(e(trim($p)));
            if ($i < count($paras) - 1) {
                $parts[] = $wm;
            }
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

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,epub,docx|max:10240',
            'status' => 'required|in:draft,published,scheduled',
            'published_at' => 'nullable|required_if:status,scheduled|date|after:now',
        ]);

        $chapter->title = $request->title;
        $chapter->content = $request->content;
        $chapter->status = $request->status;

        if ($request->status === 'scheduled') {
            $chapter->published_at = $request->published_at;
        } elseif ($request->status === 'published' && ! $chapter->published_at) {
            $chapter->published_at = now();
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

        return redirect()->route('novels.show', $novel->slug)->with('success', 'Chapter updated successfully!');
    }

    public function destroy(Novel $novel, Chapter $chapter)
    {
        Gate::authorize('delete', $chapter);

        if ($chapter->file_path) {
            Storage::disk('public')->delete($chapter->file_path);
        }

        $chapter->delete();

        return redirect()->route('novels.show', $novel->slug)->with('success', 'Chapter deleted successfully!');
    }
}
