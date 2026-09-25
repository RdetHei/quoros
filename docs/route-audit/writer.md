# Writer

Boundary lengkap: routes/web.php -> auth -> not_banned -> role:writer,admin -> prefix /writer dan name prefix writer. Admin juga dapat masuk. Gate/Policy melindungi ownership.

| Route | Handler | Model/service | Output |
|---|---|---|---|
| novels index | closure | - | redirect dashboard library |
| create + step 1 | NovelController | Novel, Auth User | writer.novels.create-step-1 |
| step 2 | NovelController | Novel, CloudinaryService | create-step-2 |
| step 3 | NovelController | Novel, Genre, Tag pivots | create-step-3 |
| store/edit/update/destroy/workspace | NovelController | Novel, Genre/Tag, NovelCharacter, CloudinaryService, Chapter | writer novel views |
| character resource | NovelCharacterController | NovelCharacter, CloudinaryService | writer.novels.characters.* |
| chapter CRUD | ChapterController | Chapter, Novel, Storage | writer.chapters.* |
| bulk upload/parse/store | ChapterController | NovelParserService -> Docx/Epub/PdfParserService | redirect/JSON |

Flow: route -> middleware -> controller -> Gate::authorize -> NovelPolicy/ChapterPolicy -> Eloquent/service -> resources/views/writer/... -> resources/views/layouts/writer.blade.php -> dashboard shells.

/dashboard -> DashboardController::writerDashboard -> User novels, Novel, Chapter, Bookmark, Review, Comment, Announcement, novel_view_logs -> resources/views/writer/dashboard.blade.php. partials/writer-nav.blade.php adalah nav writer.

Temuan: bulkCreate dan reorder route tidak memiliki method controller; writer/novels/create.blade.php tampak tidak dijangkau.

