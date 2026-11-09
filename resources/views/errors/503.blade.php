@php
    // Halaman ini dirender otomatis oleh Laravel saat mode pemeliharaan aktif.
    // Pesan kustom diambil dari cache (diisi dari Admin → Maintenance Lock).
    $message = \Illuminate\Support\Facades\Cache::get('maintenance.message')
        ?? 'Kami sedang melakukan pemeliharaan terjadwal. Silakan kembali beberapa saat lagi.';
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sedang Pemeliharaan - {{ config('app.name', 'Quoros') }}</title>

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('storage/logo/favicon-32.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600&family=Instrument+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            min-height: 100vh;
            background-color: #0d0d0e;
            color: #ddd9d0;
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        .page {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 1.5rem;
            background: radial-gradient(ellipse 80% 50% at 50% -10%, rgba(198, 165, 65, 0.14), transparent), #0d0d0e;
        }

        .content { display: grid; justify-items: center; gap: 1rem; text-align: center; max-width: 34rem; }

        .kicker { color: #c6a541; font: 500 11px 'DM Mono', ui-monospace, monospace; letter-spacing: .18em; text-transform: uppercase; }

        .title { color: #e8e3da; font: 500 clamp(2.25rem, 8vw, 3.25rem)/1.1 'Cormorant Garamond', serif; text-transform: uppercase; }

        .message { color: #a5a19a; font-size: clamp(0.95rem, 2vw, 1.05rem); line-height: 1.7; }

        .rule { width: 4rem; height: 2px; background: #c6a541; }
    </style>
</head>
<body>
    <main class="page" role="main">
        <div class="content">
            <p class="kicker">Quoros Archive</p>
            <div class="rule"></div>
            <h1 class="title">Sedang Pemeliharaan</h1>
            <p class="message">{{ $message }}</p>
        </div>
    </main>
</body>
</html>
