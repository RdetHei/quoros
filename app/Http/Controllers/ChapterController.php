<?php

namespace App\Http\Controllers;

use App\Models\Chapter;
use App\Models\Novel;
use App\Services\EpubParserService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ChapterController extends Controller
{
    public function create(Novel $novel)
    {
        if (Auth::user()->role !== 'admin' && $novel->author_id !== Auth::id()) {
            abort(403);
        }
        return view('writer.chapters.create', compact('novel'));
    }

    public function store(Request $request, Novel $novel)
    {
        if (Auth::user()->role !== 'admin' && $novel->author_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,epub,docx|max:10240', // 10MB max
        ]);

        $slug = Str::slug($request->title);
        
        $chapter = new Chapter();
        $chapter->novel_id = $novel->id;
        $chapter->title = $request->title;
        $chapter->slug = $slug;
        $chapter->content = $request->content;

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('chapters', 'public');
            $chapter->file_path = $path;
        }

        $chapter->save();

        return redirect()->route('novels.show', $novel->slug)->with('success', 'Chapter added successfully!');
    }

    public function bulkStore(Request $request, Novel $novel, EpubParserService $parser)
    {
        if (Auth::user()->role !== 'admin' && $novel->author_id !== Auth::id()) {
            abort(403);
        }

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
                    $slug = $originalSlug . '-' . $count++;
                }

                Chapter::create([
                    'novel_id' => $novel->id,
                    'title' => $data['title'],
                    'slug' => $slug,
                    'content' => $data['content'],
                ]);
            }

            return redirect()->route('novels.show', $novel->slug)
                ->with('success', count($chapters) . ' chapter berhasil diimpor dari EPUB!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses EPUB: ' . $e->getMessage());
        }
    }

    public function show(Novel $novel, $chapterSlug)
    {
        $chapter = Chapter::where('novel_id', $novel->id)
            ->where('slug', $chapterSlug)
            ->firstOrFail();
            
        $chapter->load('comments.user');

        if (Auth::check()) {
            \App\Models\ReadingHistory::updateOrCreate(
                ['user_id' => Auth::id(), 'novel_id' => $novel->id],
                ['chapter_id' => $chapter->id]
            );
        }

        $previousChapter = $chapter->previous();
        $nextChapter = $chapter->next();
        
        return view('chapters.show', compact('novel', 'chapter', 'previousChapter', 'nextChapter'));
    }

    public function edit(Novel $novel, Chapter $chapter)
    {
        if (Auth::user()->role !== 'admin' && $novel->author_id !== Auth::id()) {
            abort(403);
        }
        return view('writer.chapters.edit', compact('novel', 'chapter'));
    }

    public function update(Request $request, Novel $novel, Chapter $chapter)
    {
        if (Auth::user()->role !== 'admin' && $novel->author_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,epub,docx|max:10240',
        ]);

        $chapter->title = $request->title;
        $chapter->content = $request->content;

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
        if (Auth::user()->role !== 'admin' && $novel->author_id !== Auth::id()) {
            abort(403);
        }

        if ($chapter->file_path) {
            Storage::disk('public')->delete($chapter->file_path);
        }

        $chapter->delete();

        return redirect()->route('novels.show', $novel->slug)->with('success', 'Chapter deleted successfully!');
    }
}
