@php
    $statusCode = isset($exception) && method_exists($exception, 'getStatusCode')
        ? (int) $exception->getStatusCode()
        : 500;

    $messages = [
        401 => 'Anda perlu masuk untuk membuka halaman ini.',
        402 => 'Halaman ini membutuhkan tindakan tambahan.',
        403 => 'Anda tidak memiliki izin untuk membuka halaman ini.',
        404 => 'Halaman yang Anda cari tidak ditemukan.',
        419 => 'Sesi sudah berakhir. Silakan muat ulang halaman.',
        429 => 'Terlalu banyak permintaan. Coba lagi sebentar lagi.',
        500 => 'Terjadi kesalahan pada server.',
        503 => 'Layanan sedang tidak tersedia untuk sementara.',
    ];

    $message = $messages[$statusCode] ?? 'Terjadi kendala saat membuka halaman ini.';
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $statusCode }} - {{ config('app.name', 'Quoros') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('storage/logo/quorosLogo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html,
        body {
            min-height: 100vh;
            background-color: #020617;
            color: #e2e8f0;
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            overflow: hidden;
            -webkit-font-smoothing: antialiased;
        }

        .page {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 1.5rem;
            background:
                radial-gradient(ellipse 80% 50% at 50% -10%, rgba(99, 102, 241, 0.18), transparent),
                radial-gradient(ellipse 60% 40% at 100% 100%, rgba(79, 70, 229, 0.08), transparent),
                #020617;
        }

        .content {
            display: grid;
            justify-items: center;
            gap: 1rem;
            text-align: center;
        }

        .error-code {
            position: relative;
            color: #f8fafc;
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            font-size: clamp(4.5rem, 18vw, 9rem);
            font-weight: 800;
            line-height: 0.95;
            letter-spacing: -0.01em;
            text-shadow: 0 1.25rem 3rem rgba(15, 23, 42, 0.36);
        }

        .message {
            max-width: 30rem;
            color: #94a3b8;
            font-size: clamp(0.95rem, 2vw, 1.05rem);
            font-weight: 400;
            line-height: 1.7;
            margin-bottom: 0.75rem;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 10rem;
            min-height: 2.75rem;
            padding: 0.75rem 1.5rem;
            border: 1px solid rgba(99, 102, 241, 0.42);
            border-radius: 0.75rem;
            background: #4f46e5;
            color: #fff;
            font: inherit;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            box-shadow: 0 1rem 2.5rem rgba(79, 70, 229, 0.28);
            transition: background 160ms ease, border-color 160ms ease, transform 160ms ease;
        }

        .back-button:hover {
            border-color: #6366f1;
            background: #6366f1;
            transform: translateY(-1px);
        }

        .back-button:active {
            transform: translateY(0);
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>
<body>
    <main class="page" role="main">
        <div class="content">
            <div class="error-code" aria-label="Error {{ $statusCode }}">
                {{ $statusCode }}
            </div>

            <p class="message">{{ $message }}</p>

            <button type="button" class="back-button" onclick="window.history.length > 1 ? history.back() : window.location.assign('{{ url('/') }}')">
                Kembali
            </button>
        </div>
    </main>
</body>
</html>
