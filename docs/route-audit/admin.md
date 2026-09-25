# Admin

Boundary lengkap: routes/web.php -> auth -> not_banned -> role:admin -> prefix /admin. Layout/sidebar: resources/views/layouts/admin.blade.php.

| Route | Handler | Model/service | Output |
|---|---|---|---|
| GET /admin | AdminDashboardController::index | User, Novel, Chapter, Report | admin.dashboard |
| GET /admin/novels | NovelController::index | Novel, Genre, Tag | novels.index (bukan view admin) |
| resource /admin/genres | Admin\\GenreController | Genre <-> Novel | admin.genres.* |
| resource /admin/tags | Admin\\TagController | Tag <-> Novel | admin.tags.* |
| requests | Admin\\NovelRequestController | NovelRequest, User, InAppNotificationService | admin.requests.index/back |
| carousel | Admin\\CarouselController | Novel | admin.carousel.index/back |
| reports, ban/unban | Admin\\ReportController | Report, User, ReportStatus | admin.reports.index/back |
| users, role | Admin\\ManagementController | User | admin.users.index/back |
| moderation/content logs | ManagementController | Novel, Report, Chapter | admin views |
| announcements | Admin\\AnnouncementsController | Announcement | admin.announcements.* |
| maintenance | closure | - | admin.maintenance |

Alur dashboard: routes/web.php -> middleware -> app/Http/Controllers/Admin/AdminDashboardController.php -> User/Novel/Chapter/Report -> resources/views/admin/dashboard.blade.php -> resources/views/layouts/admin.blade.php.

NovelPolicy, ChapterPolicy, ReviewPolicy, CommentPolicy diregistrasi oleh AppServiceProvider dan memberi admin bypass lewat before(). Tidak ditemukan permission package atau Policy admin khusus.



Temuan tambahan: Route::resource untuk genres/tags tidak memakai except(['show']), tetapi kedua controller tidak memiliki method show. Dengan demikian GET /admin/genres/{genre} dan GET /admin/tags/{tag} terdaftar namun target method tidak ada.
