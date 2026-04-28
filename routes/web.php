<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReactionController;
use App\Http\Controllers\Admin\GenreController as AdminGenreController;
use App\Http\Controllers\Admin\TagController as AdminTagController;
use App\Http\Controllers\Admin\NovelRequestController as AdminNovelRequestController;
use App\Http\Controllers\Admin\CarouselController as AdminCarouselController;
use App\Http\Controllers\Api\ChapterController as ApiChapterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NovelController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [NovelController::class, 'index'])->name('home');

// API for Discord Bot
Route::get('/api/latest-chapter', [ApiChapterController::class, 'latest']);

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Public Novel Routes
Route::get('/search', [NovelController::class, 'search'])->name('novels.search');
Route::get('/novels/{novel:slug}', [NovelController::class, 'show'])->name('novels.show');
Route::get('/profile/{username}', [ProfileController::class, 'show'])->name('profile.show');
Route::get('/novels/{novel:slug}/read/{chapterSlug}', [ChapterController::class, 'show'])->name('chapters.show');
Route::get('/updated', [NovelController::class, 'updated'])->name('novels.updated');
Route::get('/genres', [NovelController::class, 'genres'])->name('genres.index');
Route::get('/tags', [NovelController::class, 'tags'])->name('tags.index');

// Auth Required Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::put('/dashboard/profile', [DashboardController::class, 'updateProfile'])->name('profile.update');
    Route::post('/dashboard/become-writer', [DashboardController::class, 'becomeWriter'])->name('dashboard.become-writer');

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
    Route::post('/reactions/{type}/{id}', [ReactionController::class, 'toggle'])->name('reactions.toggle');

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
        Route::post('/writer/novels/{novel}/chapters/bulk', [ChapterController::class, 'bulkStore'])->name('writer.chapters.bulk');
        Route::get('/writer/novels/{novel}/chapters/{chapter}/edit', [ChapterController::class, 'edit'])->name('writer.chapters.edit');
        Route::put('/writer/novels/{novel}/chapters/{chapter}', [ChapterController::class, 'update'])->name('writer.chapters.update');
        Route::delete('/writer/novels/{novel}/chapters/{chapter}', [ChapterController::class, 'destroy'])->name('writer.chapters.destroy');
    });

    // Admin Only
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/novels', [NovelController::class, 'index'])->name('admin.novels.index');
        Route::resource('/admin/genres', AdminGenreController::class, ['as' => 'admin']);
        Route::resource('/admin/tags', AdminTagController::class, ['as' => 'admin']);
        
        // Novel Requests Management
        Route::get('/admin/requests', [AdminNovelRequestController::class, 'index'])->name('admin.requests.index');
        Route::patch('/admin/requests/{novelRequest}/status', [AdminNovelRequestController::class, 'updateStatus'])->name('admin.requests.status');
        Route::delete('/admin/requests/{novelRequest}', [AdminNovelRequestController::class, 'destroy'])->name('admin.requests.destroy');

        // Carousel Management
        Route::get('/admin/carousel', [AdminCarouselController::class, 'index'])->name('admin.carousel.index');
        Route::post('/admin/carousel/{novel}/toggle', [AdminCarouselController::class, 'toggle'])->name('admin.carousel.toggle');
    });
});
