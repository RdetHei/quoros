<?php

use App\Http\Controllers\GuideController;
use App\Http\Controllers\Admin\CarouselController as AdminCarouselController;
use App\Http\Controllers\Admin\GenreController as AdminGenreController;
use App\Http\Controllers\Admin\ManagementController as AdminManagementController;
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
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AnnouncementsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [NovelController::class, 'landing'])->name('welcome');
Route::get('/home', [NovelController::class, 'index'])->name('home');

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
Route::get('/api/novel-details/{novel}', [LiveSearchController::class, 'details']);

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
    Route::get('/settings/v2', [SettingsController::class, 'indexV2'])->name('settings.v2');
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

    // Writer & Admin Routes (Workspace)
    Route::middleware('role:writer,admin')->prefix('writer')->name('writer.')->group(function () {

        // Novel Management
        Route::prefix('novels')->name('novels.')->group(function () {
            Route::get('/', function() { return redirect()->route('dashboard', ['tab' => 'library']); })->name('index');
            Route::get('/create', [NovelController::class, 'create'])->name('create');
            Route::get('/create/step-1', [NovelController::class, 'createStep1'])->name('create.step-1');
            Route::post('/create/step-1', [NovelController::class, 'storeStep1'])->name('store.step-1');
            Route::get('/{novel}/create/step-2', [NovelController::class, 'createStep2'])->name('create.step-2');
            Route::put('/{novel}/create/step-2', [NovelController::class, 'updateStep2'])->name('update.step-2');
            Route::get('/{novel}/create/step-3', [NovelController::class, 'createStep3'])->name('create.step-3');
            Route::put('/{novel}/create/step-3', [NovelController::class, 'updateStep3'])->name('update.step-3');
            Route::post('/', [NovelController::class, 'store'])->name('store');
            Route::get('/{novel}/edit', [NovelController::class, 'edit'])->name('edit');
            Route::put('/{novel}', [NovelController::class, 'update'])->name('update');
            Route::delete('/{novel}', [NovelController::class, 'destroy'])->name('destroy');
            Route::get('/{novel}/workspace', [NovelController::class, 'workspace'])->name('workspace');

            Route::resource('{novel}/characters', NovelCharacterController::class)
                ->except(['show'])
                ->names('characters');

            // Chapters Management
            Route::prefix('{novel}/chapters')->name('chapters.')->group(function () {
                Route::get('/create', [ChapterController::class, 'create'])->name('create');
                Route::post('/', [ChapterController::class, 'store'])->name('store');
                Route::get('/bulk', [ChapterController::class, 'bulkCreate'])->name('bulk-create');
                Route::post('/bulk-upload', [ChapterController::class, 'bulkStore'])->name('bulk-upload');
                Route::post('/bulk-parse', [ChapterController::class, 'parseDocument'])->name('bulk-parse');
                Route::post('/bulk-store', [ChapterController::class, 'storeBulkChapter'])->name('store-bulk');
                Route::post('/reorder', [ChapterController::class, 'reorder'])->name('reorder');
                Route::get('/{chapter}/edit', [ChapterController::class, 'edit'])->name('edit');
                Route::put('/{chapter}', [ChapterController::class, 'update'])->name('update');
                Route::delete('/{chapter}', [ChapterController::class, 'destroy'])->name('destroy');
            });
        });
    });

    // Admin Only
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        // Admin dashboard entry point
        Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        Route::get('/novels', [NovelController::class, 'index'])->name('admin.novels.index');
        Route::resource('genres', AdminGenreController::class, ['as' => 'admin']);
        Route::resource('tags', AdminTagController::class, ['as' => 'admin']);

        // Novel Requests Management (Novel Moderation: Approve/Reject)
        Route::get('/requests', [AdminNovelRequestController::class, 'index'])->name('admin.requests.index');
        Route::patch('/requests/{novelRequest}/status', [AdminNovelRequestController::class, 'updateStatus'])->name('admin.requests.status');
        Route::delete('/requests/{novelRequest}', [AdminNovelRequestController::class, 'destroy'])->name('admin.requests.destroy');

        // Carousel Management (Feature)
        Route::get('/carousel', [AdminCarouselController::class, 'index'])->name('admin.carousel.index');
        Route::post('/carousel/{novel}/toggle', [AdminCarouselController::class, 'toggle'])->name('admin.carousel.toggle');

        // Reports & moderation
        Route::get('/reports', [AdminReportController::class, 'index'])->name('admin.reports.index');
        Route::patch('/reports/{report}', [AdminReportController::class, 'update'])->name('admin.reports.update');
        Route::post('/users/{user}/ban', [AdminReportController::class, 'banUser'])->name('admin.users.ban');
        Route::post('/users/{user}/unban', [AdminReportController::class, 'unban'])->name('admin.users.unban');

        // User Management
        Route::get('/users', [AdminManagementController::class, 'users'])->name('admin.users.index');
        Route::patch('/users/{user}/role', [AdminManagementController::class, 'updateRole'])->name('admin.users.role.update');

        // Content Moderation + Logs
        Route::get('/moderation', [AdminManagementController::class, 'moderation'])->name('admin.moderation.index');
        Route::get('/content-logs', [AdminManagementController::class, 'contentLogs'])->name('admin.content-logs.index');

        // Platform Settings
        Route::get('/announcements', [AnnouncementsController::class, 'index'])->name('admin.announcements.index');
        Route::get('/announcements/create', [AnnouncementsController::class, 'create'])->name('admin.announcements.create');
        Route::post('/announcements', [AnnouncementsController::class, 'store'])->name('admin.announcements.store');

        // System
        Route::get('/maintenance', function () {
            return view('admin.maintenance');
        })->name('admin.maintenance');
    });
});
