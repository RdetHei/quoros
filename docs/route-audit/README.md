# Route & Role Audit

Audit statis 25 Sep 2026. Laravel Blade/Eloquent; tidak ditemukan Livewire, Inertia, atau Repository. Route utama routes/web.php; guard session web di config/auth.php. Role enum: admin, writer, user.

## Struktur
- Public: tanpa auth; baca bab throttle:chapter-read.
- Shared: auth + not_banned.
- Writer: shared + role:writer,admin; prefix/name writer.
- Admin: shared + role:admin; prefix admin.
- /dashboard: admin -> admin.dashboard; writer -> writer.dashboard; user/default -> user.dashboard.

## Temuan
1. bulkCreate dan reorder terdaftar pada route writer tetapi tidak ada di ChapterController.
2. writer/novels/create.blade.php tampak tidak terhubung; route create memakai wizard.
3. GET /requests mengambil semua NovelRequest, tidak difilter user.
4. POST /logout tidak memiliki auth middleware eksplisit.
5. routes/api.php memakai auth:sanctum, tetapi config/auth.php hanya mendaftarkan guard web.

Dokumen terkait: admin.md, user.md, writer.md, authentication.md, file-map.md.


7. Resource /admin/genres dan /admin/tags secara default mendaftarkan GET {resource}, tetapi GenreController dan TagController tidak mendeklarasikan method show; route show tersebut tampak akan gagal saat dispatch.
