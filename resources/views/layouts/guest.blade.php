<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Restoranku') }} | Masuk</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            color-scheme: light;
            --auth-ink: #202821;
            --auth-muted: #788078;
            --auth-green: #173d30;
            --auth-green-deep: #102f25;
            --auth-lime: #d6e8b5;
            --auth-paper: #f7f8f4;
            --auth-line: #e4e9e1;
        }

        * { box-sizing: border-box; }

        body.auth-body {
            min-width: 320px;
            min-height: 100vh;
            margin: 0;
            color: var(--auth-ink);
            background: var(--auth-paper);
            font-family: 'Figtree', sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        .auth-layout {
            display: grid;
            grid-template-columns: minmax(390px, 0.94fr) minmax(480px, 1.06fr);
            min-height: 100vh;
        }

        .auth-visual {
            position: relative;
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
            padding: 42px clamp(34px, 5vw, 76px) 36px;
            color: #fff;
            background:
                radial-gradient(ellipse at 10% 92%, rgb(214 232 181 / 12%), transparent 35%),
                radial-gradient(ellipse at 92% 12%, rgb(214 232 181 / 9%), transparent 34%),
                linear-gradient(145deg, var(--auth-green), var(--auth-green-deep));
        }

        .auth-visual::before,
        .auth-visual::after {
            position: absolute;
            z-index: 0;
            width: 430px;
            height: 430px;
            border: 1px solid rgb(255 255 255 / 8%);
            border-radius: 50%;
            content: '';
            pointer-events: none;
        }

        .auth-visual::before { top: 21%; right: -300px; }
        .auth-visual::after { right: -250px; bottom: -300px; width: 560px; height: 560px; }

        .auth-brand,
        .auth-visual-content,
        .auth-visual-footer { position: relative; z-index: 1; }

        .auth-brand {
            display: inline-flex;
            width: fit-content;
            align-items: center;
            gap: 12px;
            color: #fff;
            text-decoration: none;
        }

        .auth-brand-mark {
            display: grid;
            width: 44px;
            height: 44px;
            place-items: center;
            border: 1px solid rgb(255 255 255 / 22%);
            border-radius: 14px;
            color: var(--auth-green-deep);
            background: var(--auth-lime);
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -1px;
        }

        .auth-brand-name { display: block; font-size: 17px; font-weight: 800; letter-spacing: -.35px; }
        .auth-brand-caption { display: block; margin-top: 2px; color: rgb(255 255 255 / 58%); font-size: 9px; font-weight: 700; letter-spacing: 1.5px; }

        .auth-visual-content { max-width: 520px; margin: auto 0; padding: 70px 0; }

        .auth-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            color: var(--auth-lime);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .auth-eyebrow::before { width: 23px; height: 1px; background: currentColor; content: ''; }

        .auth-visual h1 {
            max-width: 520px;
            margin: 22px 0 18px;
            color: #fff;
            font-size: clamp(42px, 5vw, 66px);
            font-weight: 600;
            letter-spacing: -2.9px;
            line-height: 1.04;
        }

        .auth-visual h1 span { color: var(--auth-lime); }
        .auth-visual-copy { max-width: 405px; margin: 0; color: rgb(255 255 255 / 67%); font-size: 16px; line-height: 1.75; }

        .auth-feature {
            display: flex;
            max-width: 390px;
            align-items: center;
            gap: 14px;
            margin-top: 44px;
            padding: 15px 17px;
            border: 1px solid rgb(255 255 255 / 13%);
            border-radius: 16px;
            background: rgb(255 255 255 / 6%);
            backdrop-filter: blur(8px);
        }

        .auth-feature-icon {
            display: grid;
            width: 43px;
            height: 43px;
            flex: 0 0 auto;
            place-items: center;
            border-radius: 13px;
            color: var(--auth-lime);
            background: rgb(214 232 181 / 12%);
        }

        .auth-feature strong { display: block; color: #fff; font-size: 13px; font-weight: 700; }
        .auth-feature span { display: block; margin-top: 4px; color: rgb(255 255 255 / 56%); font-size: 11px; }
        .auth-feature-dot { width: 7px; height: 7px; flex: 0 0 auto; margin-left: auto; border-radius: 50%; background: #a6d17a; box-shadow: 0 0 0 5px rgb(166 209 122 / 12%); }
        .auth-visual-footer { color: rgb(255 255 255 / 50%); font-size: 11px; letter-spacing: .2px; }

        .auth-panel {
            display: grid;
            min-height: 100vh;
            place-items: center;
            padding: 56px clamp(28px, 7vw, 104px);
            background:
                radial-gradient(ellipse at 100% 0%, rgb(220 229 208 / 34%), transparent 35%),
                var(--auth-paper);
        }

        .auth-panel-inner { width: min(100%, 430px); }
        .auth-mobile-brand { display: none; }

        .auth-heading { margin-bottom: 32px; }
        .auth-heading-label { margin: 0 0 12px; color: #6f8f64; font-size: 10px; font-weight: 800; letter-spacing: 1.8px; text-transform: uppercase; }
        .auth-heading h2 { margin: 0; color: var(--auth-ink); font-size: clamp(30px, 3vw, 38px); font-weight: 700; letter-spacing: -1.4px; line-height: 1.15; }
        .auth-heading p { margin: 12px 0 0; color: var(--auth-muted); font-size: 14px; line-height: 1.6; }

        .auth-status { margin: 0 0 20px; padding: 12px 14px; border: 1px solid #d8ead3; border-radius: 10px; color: #315d37; background: #f0f8ed; font-size: 13px; }

        .auth-form { display: grid; gap: 20px; }
        .auth-field label { display: block; margin-bottom: 8px; color: #354139; font-size: 12px; font-weight: 700; }
        .auth-input-wrap { position: relative; }
        .auth-input-icon { position: absolute; top: 50%; left: 15px; width: 18px; height: 18px; color: #8d978e; transform: translateY(-50%); pointer-events: none; }
        .auth-input {
            display: block;
            width: 100%;
            height: 52px;
            padding: 0 15px 0 45px;
            border: 1px solid var(--auth-line);
            border-radius: 11px;
            outline: none;
            color: var(--auth-ink);
            background: #fff;
            font: inherit;
            font-size: 14px;
            transition: border-color .18s ease, box-shadow .18s ease;
        }

        .auth-input::placeholder { color: #a5aca5; }
        .auth-input:focus { border-color: #77976c; box-shadow: 0 0 0 4px rgb(111 143 100 / 12%); }
        .auth-input.is-invalid { border-color: #d06b62; }
        .auth-error { margin: 7px 0 0; color: #b44238; font-size: 12px; line-height: 1.45; }

        .auth-options { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-top: -2px; }
        .auth-remember { display: inline-flex; align-items: center; gap: 9px; color: #626d64; font-size: 12px; cursor: pointer; }
        .auth-remember input { width: 15px; height: 15px; margin: 0; accent-color: var(--auth-green); }
        .auth-link { color: #426c4b; font-size: 12px; font-weight: 700; text-decoration: none; }
        .auth-link:hover { color: #244c33; text-decoration: underline; }

        .auth-submit {
            display: flex;
            width: 100%;
            min-height: 52px;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 4px;
            border: 0;
            border-radius: 11px;
            color: #fff;
            background: var(--auth-green);
            box-shadow: 0 8px 18px rgb(23 61 48 / 16%);
            font: inherit;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: background .18s ease, transform .18s ease, box-shadow .18s ease;
        }

        .auth-submit:hover { background: #20523e; box-shadow: 0 10px 22px rgb(23 61 48 / 22%); transform: translateY(-1px); }
        .auth-submit:focus-visible { outline: 3px solid #a8c394; outline-offset: 3px; }
        .auth-panel-footer { margin-top: 35px; color: #969e96; font-size: 11px; text-align: center; }
        .auth-panel-footer a { margin-left: 7px; color: #5c7659; font-weight: 700; text-decoration: none; }
        .auth-panel-footer a:hover { text-decoration: underline; }

        @media (max-width: 900px) {
            .auth-layout { grid-template-columns: minmax(300px, .8fr) minmax(430px, 1.2fr); }
            .auth-visual { padding-right: 32px; padding-left: 32px; }
            .auth-panel { padding-right: 42px; padding-left: 42px; }
            .auth-visual h1 { font-size: 48px; }
        }

        @media (max-width: 720px) {
            .auth-layout { display: block; }
            .auth-visual { display: none; }
            .auth-brand-mark { width: 40px; height: 40px; border-radius: 12px; }
            .auth-panel { min-height: 100vh; padding: 42px 24px 32px; }
            .auth-panel-inner { max-width: 430px; }
            .auth-mobile-brand { display: block; margin-bottom: 38px; }
            .auth-mobile-brand .auth-brand { color: var(--auth-green); }
            .auth-mobile-brand .auth-brand-caption { color: #849084; }
            .auth-mobile-brand .auth-brand-mark { border-color: #dfe6d9; }
            .auth-heading { margin-bottom: 28px; }
        }

        @media (max-width: 420px) {
            .auth-panel { padding-right: 20px; padding-left: 20px; }
            .auth-options { align-items: flex-start; }
            .auth-heading h2 { font-size: 32px; }
        }
    </style>
</head>
<body class="auth-body">
    <main class="auth-layout">
        <aside class="auth-visual" aria-label="Informasi Restoranku">
            <a class="auth-brand" href="{{ route('menu') }}" aria-label="Restoranku, halaman utama">
                <span class="auth-brand-mark" aria-hidden="true">R</span>
                <span>
                    <span class="auth-brand-name">Restoranku</span>
                    <span class="auth-brand-caption">MANAJEMEN RESTORAN</span>
                </span>
            </a>

            <div class="auth-visual-content">
                <p class="auth-eyebrow">Portal operasional</p>
                <h1>Rasa terbaik dimulai dari <span>layanan yang tertata.</span></h1>
                <p class="auth-visual-copy">
                    Kelola pesanan dan operasional restoran dari satu tempat, dengan alur kerja yang lebih jelas setiap hari.
                </p>

                <div class="auth-feature">
                    <span class="auth-feature-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" width="22" height="22">
                            <path d="M4 10.5 12 4l8 6.5v8a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 4 18.5v-8Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/>
                            <path d="M9 20v-6h6v6M8 10h.01M12 10h.01M16 10h.01" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                        </svg>
                    </span>
                    <span>
                        <strong>Satu ruang kendali</strong>
                        <span>Pesanan, menu, dan tim restoran</span>
                    </span>
                    <span class="auth-feature-dot" aria-hidden="true"></span>
                </div>
            </div>

            <div class="auth-visual-footer">Melayani dengan rasa, mengelola dengan cermat.</div>
        </aside>

        <section class="auth-panel" aria-label="Form masuk">
            <div class="auth-panel-inner">
                <div class="auth-mobile-brand">
                    <a class="auth-brand" href="{{ route('menu') }}">
                        <span class="auth-brand-mark" aria-hidden="true">R</span>
                        <span>
                            <span class="auth-brand-name">Restoranku</span>
                            <span class="auth-brand-caption">MANAJEMEN RESTORAN</span>
                        </span>
                    </a>
                </div>

                {{ $slot }}

                <div class="auth-panel-footer">
                    &copy; {{ date('Y') }} Restoranku · Dibuat oleh Ilham Hilmi Hilmawan
                    <a href="{{ route('menu') }}">Kembali ke halaman utama</a>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
