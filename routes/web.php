<?php

use App\Http\Controllers\GuideController;
use App\Http\Controllers\Admin\CarouselController as AdminCarouselController;
use App\Http\Controllers\Admin\GenreController as AdminGenreController;
use App\Http\Controllers\Admin\NovelRequestController as AdminNovelRequestController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Admin\TagController as AdminTagController;
use App\Http\Controllers\Api\ChapterController as ApiChapterController;
use App\Http\Controllers\Api\LiveSearchController;
use App\Http\Controllers\AuthorFollowController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserListController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\NovelController;
use App\Http\Controllers\NovelCharacterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReactionController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Writer\StatsController as WriterStatsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [NovelController::class, 'index'])->name('home');
Route::get('/welcome', [NovelController::class, 'landing'])->name('welcome');

Route::get('/site.webmanifest', function () {
    $logo = asset('storage/logo/quorosLogo.png');

    return response()->json([
        'name' => config('app.name', 'Quoros'),
        'short_name' => 'Quoros',
        'description' => 'Baca novel — instal sebagai aplikasi; bab yang sudah dibuka dapat dibaca offline.',
        'start_url' => '/',
        'scope' => '/',
        'display' => 'standalone',
        'background_color' => '#020617',
        'theme_color' => '#0f172a',
        'lang' => 'id',
        'icons' => [
            ['src' => $logo, 'sizes' => '192x192', 'type' => 'image/png', 'purpose' => 'any'],
            ['src' => $logo, 'sizes' => '512x512', 'type' => 'image/png', 'purpose' => 'any maskable'],
        ],
    ], 200, ['Content-Type' => 'application/manifest+json'], JSON_UNESCAPED_SLASHES);
})->name('pwa.manifest');

// Public API (Discord bot integration can use this after production deploy)
Route::get('/api/latest-chapter', [ApiChapterController::class, 'latest']);
Route::get('/api/live-search', [LiveSearchController::class, 'search']);

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
Route::get('/profile/{username}/lists/{list:slug}', [UserListController::class, 'showPublic'])->name('lists.public');
Route::get('/novels/{novel:slug}/read/{chapterSlug}', [ChapterController::class, 'show'])
    ->middleware('throttle:chapter-read')
    ->name('chapters.show');
Route::get('/updated', [NovelController::class, 'updated'])->name('novels.updated');
Route::get('/trending', [NovelController::class, 'trending'])->name('novels.trending');
Route::get('/genres', [NovelController::class, 'genres'])->name('genres.index');
Route::get('/tags', [NovelController::class, 'tags'])->name('tags.index');

// Guide Routes
Route::get('/guides', [GuideController::class, 'index'])->name('guides.index');
Route::get('/guides/{category:slug}', [GuideController::class, 'category'])->name('guides.category');
Route::get('/guides/{category:slug}/{article:slug}', [GuideController::class, 'show'])->name('guides.show');

// Auth Required Routes
Route::middleware(['auth', 'not_banned'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/settings', [DashboardController::class, 'settings'])->name('settings');
    Route::put('/dashboard/profile', [DashboardController::class, 'updateProfile'])->name('profile.update');
    Route::post('/dashboard/profile/photo', [ProfileController::class, 'updateProfilePhoto'])->name('profile.photo.update');
    Route::post('/dashboard/become-writer', [DashboardController::class, 'becomeWriter'])->name('dashboard.become-writer');

    // Bookmark & History dedicated views
    Route::get('/bookmarks', [BookmarkController::class, 'index'])->name('bookmarks.index');
    Route::get('/history', [NovelController::class, 'history'])->name('history.index');

    // Request Novel
    Route::get('/requests', [NovelController::class, 'requests'])->name('requests.index');
    Route::post('/requests', [NovelController::class, 'storeRequest'])->name('requests.store');

    // Bookmark
    Route::post('/novels/{novel}/bookmark', [BookmarkController::class, 'toggle'])->name('bookmarks.toggle');

    // In-app notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read-all');

    // Reviews & Comments
    Route::post('/novels/{novel}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::post('/chapters/{chapter}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/reactions/{type}/{id}', [ReactionController::class, 'toggle'])->name('reactions.toggle');

    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');

    Route::post('/users/{user}/follow', [AuthorFollowController::class, 'toggle'])->name('authors.follow');

    Route::get('/lists', [UserListController::class, 'index'])->name('lists.index');
    Route::get('/lists/create', [UserListController::class, 'create'])->name('lists.create');
    Route::post('/lists', [UserListController::class, 'store'])->name('lists.store');
    Route::get('/lists/{list:slug}', [UserListController::class, 'show'])->name('lists.show');
    Route::get('/lists/{list:slug}/edit', [UserListController::class, 'edit'])->name('lists.edit');
    Route::put('/lists/{list:slug}', [UserListController::class, 'update'])->name('lists.update');
    Route::delete('/lists/{list:slug}', [UserListController::class, 'destroy'])->name('lists.destroy');
    Route::post('/lists/{list:slug}/novels/{novel}', [UserListController::class, 'addNovel'])->name('lists.novels.add');
    Route::delete('/lists/{list:slug}/novels/{novel}', [UserListController::class, 'removeNovel'])->name('lists.novels.remove');

    // Writer & Admin Routes
    Route::middleware('role:writer,admin')->group(function () {
        Route::get('/writer/bulk-guide', function () {
            return view('writer.bulk-guide');
        })->name('writer.bulk-guide');

        Route::get('/writer/stats', [WriterStatsController::class, 'index'])->name('writer.stats');
        Route::get('/writer/novels', [NovelController::class, 'writerIndex'])->name('writer.novels.index');
        Route::get('/writer/novels/create/step-1', [NovelController::class, 'createStep1'])->name('writer.novels.create.step-1');
        Route::post('/writer/novels/create/step-1', [NovelController::class, 'storeStep1'])->name('writer.novels.store.step-1');
        Route::get('/writer/novels/{novel}/create/step-2', [NovelController::class, 'createStep2'])->name('writer.novels.create.step-2');
        Route::put('/writer/novels/{novel}/create/step-2', [NovelController::class, 'updateStep2'])->name('writer.novels.update.step-2');
        Route::get('/writer/novels/{novel}/create/step-3', [NovelController::class, 'createStep3'])->name('writer.novels.create.step-3');
        Route::put('/writer/novels/{novel}/create/step-3', [NovelController::class, 'updateStep3'])->name('writer.novels.update.step-3');
        Route::get('/writer/novels/create', [NovelController::class, 'create'])->name('writer.novels.create');
        Route::post('/writer/novels', [NovelController::class, 'store'])->name('writer.novels.store');
        Route::resource('/writer/novels/{novel}/characters', NovelCharacterController::class)
            ->except(['show'])
            ->names('writer.novels.characters');
        Route::get('/writer/novels/{novel}/edit', [NovelController::class, 'edit'])->name('writer.novels.edit');
        Route::put('/writer/novels/{novel}', [NovelController::class, 'update'])->name('writer.novels.update');
        Route::delete('/writer/novels/{novel}', [NovelController::class, 'destroy'])->name('writer.novels.destroy');

        // Chapters Management
        Route::get('/writer/novels/{novel}/chapters/create', [ChapterController::class, 'create'])->name('writer.chapters.create');
        Route::post('/writer/novels/{novel}/chapters', [ChapterController::class, 'store'])->name('writer.chapters.store');
        Route::post('/writer/novels/{novel}/chapters/bulk-upload', [ChapterController::class, 'bulkStore'])->name('writer.chapters.bulk-store');
        Route::post('/writer/novels/{novel}/chapters/bulk-parse', [ChapterController::class, 'parseDocument'])->name('writer.chapters.parse-epub');
        Route::post('/writer/novels/{novel}/chapters/bulk-store', [ChapterController::class, 'storeBulkChapter'])->name('writer.chapters.store-bulk');
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

        // Reports & moderation
        Route::get('/admin/reports', [AdminReportController::class, 'index'])->name('admin.reports.index');
        Route::patch('/admin/reports/{report}', [AdminReportController::class, 'update'])->name('admin.reports.update');
        Route::post('/admin/users/{user}/ban', [AdminReportController::class, 'banUser'])->name('admin.users.ban');
        Route::post('/admin/users/{user}/unban', [AdminReportController::class, 'unban'])->name('admin.users.unban');
    });
});
