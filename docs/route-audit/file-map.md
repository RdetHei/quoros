# File map

## Infrastruktur
bootstrap/app.php -> routes/web.php/routes/api.php -> app/Http/Middleware/SecurityHeadersMiddleware.php, RoleMiddleware.php, EnsureNotBanned.php.

config/auth.php -> app/Models/User.php -> database/migrations/0001_01_01_000000_create_users_table.php.

app/Providers/AppServiceProvider.php -> app/Policies/NovelPolicy.php, ChapterPolicy.php, ReviewPolicy.php, CommentPolicy.php.

## Flow role
routes/web.php -> app/Http/Controllers/AuthController.php -> app/Models/User.php -> resources/views/auth/login.blade.php or register.blade.php -> resources/views/layouts/auth.blade.php.

routes/web.php -> app/Http/Controllers/Admin/AdminDashboardController.php -> User/Novel/Chapter/Report -> resources/views/admin/dashboard.blade.php -> resources/views/layouts/admin.blade.php.

routes/web.php -> app/Http/Controllers/Admin/{Genre,Tag,NovelRequest,Carousel,Report,Management,Announcements}Controller.php -> model terkait + app/Services/InAppNotificationService.php -> resources/views/admin/.

routes/web.php -> DashboardController::readerDashboard -> User/ReadingHistory/Bookmark/Novel/Chapter -> resources/views/user/dashboard.blade.php -> dashboard layouts.

routes/web.php -> NovelController -> NovelPolicy -> Novel <-> Genre/Tag + app/Services/CloudinaryService.php -> resources/views/writer/novels/.

routes/web.php -> ChapterController -> NovelPolicy/ChapterPolicy -> Chapter + NovelParserService -> Docx/Epub/PdfParserService -> writer chapter views or JSON.

routes/web.php -> NovelController::show/ChapterController::show -> NovelViewService, NovelViewLog/ReadingHistory/Comment/Reaction -> novel/chapter views.

## Navigation
resources/views/layouts/app.blade.php -> public/user nav + notification bell (AppServiceProvider composer). layouts/admin.blade.php -> admin sidebar. layouts/writer.blade.php -> dashboard layouts -> writer header/sidebar; partials/writer-nav.blade.php adalah nav writer.

