# User / Reader

user adalah role database; Reader adalah label UI. Semua route protected melewati auth + not_banned, sehingga writer/admin juga dapat mengakses route shared.

| Route | Handler | Model/service | Output |
|---|---|---|---|
| /dashboard | DashboardController::readerDashboard | User, ReadingHistory, Bookmark, Novel, Chapter | user.dashboard |
| settings/profile | DashboardController, SettingsController | User, CloudinaryService | settings.index/back |
| become-writer | DashboardController::becomeWriter | User.role | back |
| bookmarks | BookmarkController | Bookmark, ReadingHistory, Chapter, Novel | user.bookmarks/back/JSON |
| history/requests | NovelController | ReadingHistory, NovelRequest | user.history/user.requests |
| notifications | NotificationController | InAppNotification | notifications.index/JSON |
| review/comment/reaction/report/follow | controllers terkait | Review, Comment, Reaction, Report, AuthorFollow | back/JSON |
| CRUD lists | UserListController | UserList, UserListItem, Novel | user.lists.* |

Flow dashboard: /dashboard -> DashboardController.php -> User/ReadingHistory/Bookmark/Novel/Chapter -> resources/views/user/dashboard.blade.php -> resources/views/layouts/dashboard.blade.php -> layouts/dashboard-shell.blade.php.

Nav reader di resources/views/layouts/app.blade.php. Delete review/comment memakai ownership Policy; UserList memakai abort_unless owner/public. User biasa hanya melihat bab published; author/admin dapat melihat draft. User bisa mempromosikan diri menjadi writer.

