{{-- @php
    $roleName = auth()->user()?->role?->role_name;
    $backUrl = match ($roleName) {
        'admin' => route('dashboard'),
        'cashier', 'chef' => route('orders.index'),
        default => route('menu'),
    };
@endphp --}}

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - Akses Ditolak</title>
    <style>
        * { box-sizing: border-box; }
        body {
            min-height: 100vh;
            margin: 0;
            display: grid;
            place-items: center;
            padding: 24px;
            background: #f4f6f8;
            color: #263238;
            font-family: Arial, sans-serif;
        }
        main {
            width: min(100%, 520px);
            padding: 40px 32px;
            border-radius: 16px;
            background: #fff;
            box-shadow: 0 12px 36px rgb(20 40 60 / 10%);
            text-align: center;
        }
        .code { margin: 0; color: #dc3545; font-size: 64px; line-height: 1; }
        h1 { margin: 16px 0 8px; font-size: 25px; }
        p { margin: 0 0 24px; color: #667085; line-height: 1.6; }
        a {
            display: inline-block;
            padding: 11px 22px;
            border-radius: 8px;
            background: #435ebe;
            color: #fff;
            font-weight: 600;
            text-decoration: none;
        }
        a:hover { background: #364a9a; }
        a:focus-visible { outline: 3px solid #a8b5f0; outline-offset: 3px; }
    </style>
</head>
<body>
    <main>
        <p class="code">403</p>
        <h1>Akses Ditolak</h1>
        <p>Akun Anda tidak memiliki izin untuk membuka halaman ini.</p>
        {{-- <a href="{{ $backUrl }}">Kembali</a> --}}
    </main>
</body>
</html>
