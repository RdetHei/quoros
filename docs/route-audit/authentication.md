# Authentication

config/auth.php memakai guard session web dan provider Eloquent App\\Models\\User. bootstrap/app.php mendaftarkan alias middleware; migration users menentukan role enum.

| Route | Middleware | Flow |
|---|---|---|
| GET /login | guest | AuthController::showLogin -> resources/views/auth/login.blade.php -> layouts.auth |
| POST /login | guest | validate -> Auth::attempt -> cek ban -> regenerate session -> user home; writer/admin dashboard |
| GET /register | guest | showRegister -> auth.register -> layouts.auth |
| POST /register | guest | User::create role user -> Auth::login -> home |
| POST /logout | none explicit | logout -> invalidate/regenerate token -> / |

Writer/admin login ke dashboard lalu DashboardController redirect ke dashboard role masing-masing. Ban dicek saat login dan EnsureNotBanned untuk session aktif; admin dikecualikan model. Perubahan role: self-promotion user->writer atau admin updateRole; tidak ditemukan permission table/package.

