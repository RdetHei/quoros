<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NovelController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', [NovelController::class, 'index'])->name('home');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Public Novel Routes
Route::get('/novels/{novel:slug}', [NovelController::class, 'show'])->name('novels.show');
Route::get('/novels/{novel:slug}/read/{chapterSlug}', [ChapterController::class, 'show'])->name('chapters.show');
Route::get('/updated', [NovelController::class, 'updated'])->name('novels.updated');
Route::get('/genres', [NovelController::class, 'genres'])->name('genres.index');
Route::get('/tags', [NovelController::class, 'tags'])->name('tags.index');

// Auth Required Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();
        $bookmarks = $user->bookmarks()->with('novel.author')->latest()->take(6)->get();
        $histories = $user->readingHistories()->with(['novel', 'chapter'])->latest()->take(6)->get();
        
        return view('dashboard', compact('bookmarks', 'histories'));
    })->name('dashboard');

    // Bookmark & History dedicated views
    Route::get('/bookmarks', [BookmarkController::class, 'index'])->name('bookmarks.index');
    Route::get('/history', [NovelController::class, 'history'])->name('history.index');
    
    // Request Novel
    Route::get('/requests', [NovelController::class, 'requests'])->name('requests.index');
    Route::post('/requests', [NovelController::class, 'storeRequest'])->name('requests.store');

    // Bookmark
    Route::post('/novels/{novel}/bookmark', [BookmarkController::class, 'toggle'])->name('bookmarks.toggle');

    // Reviews & Comments
    Route::post('/novels/{novel}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::post('/chapters/{chapter}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Writer & Admin Routes
    Route::middleware('role:writer,admin')->group(function () {
        Route::get('/writer/novels', [NovelController::class, 'writerIndex'])->name('writer.novels.index');
        Route::get('/writer/novels/create', [NovelController::class, 'create'])->name('writer.novels.create');
        Route::post('/writer/novels', [NovelController::class, 'store'])->name('writer.novels.store');
        Route::get('/writer/novels/{novel}/edit', [NovelController::class, 'edit'])->name('writer.novels.edit');
        Route::put('/writer/novels/{novel}', [NovelController::class, 'update'])->name('writer.novels.update');
        Route::delete('/writer/novels/{novel}', [NovelController::class, 'destroy'])->name('writer.novels.destroy');

        // Chapters Management
        Route::get('/writer/novels/{novel}/chapters/create', [ChapterController::class, 'create'])->name('writer.chapters.create');
        Route::post('/writer/novels/{novel}/chapters', [ChapterController::class, 'store'])->name('writer.chapters.store');
        Route::get('/writer/novels/{novel}/chapters/{chapter}/edit', [ChapterController::class, 'edit'])->name('writer.chapters.edit');
        Route::put('/writer/novels/{novel}/chapters/{chapter}', [ChapterController::class, 'update'])->name('writer.chapters.update');
        Route::delete('/writer/novels/{novel}/chapters/{chapter}', [ChapterController::class, 'destroy'])->name('writer.chapters.destroy');
    });

    // Admin Only
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/novels', [NovelController::class, 'index'])->name('admin.novels.index');
    });
});
