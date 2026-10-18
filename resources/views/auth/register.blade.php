<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar | Nalar</title>
    <meta name="theme-color" content="#FFFCF8">
    <style>
        :root {
            --primary: #EA580C;
            --primary-hover: #C2410C;
            --accent: #FB923C;
            --soft: #FFEDD5;
            --bg: #FFFCF8;
            --text: #292524;
            --highlight: #2563EB;
            --muted: rgba(41, 37, 36, .72);
            --line: rgba(41, 37, 36, .16);
            --serif: "Iowan Old Style", "Palatino Linotype", Palatino, "Book Antiqua", Georgia, serif;
            --sans: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
        *, *::before, *::after { box-sizing: border-box; }
        body {
            margin: 0; min-height: 100vh; display: grid; place-items: center;
            padding: 24px; background: var(--bg); color: var(--text);
            font-family: var(--sans); -webkit-font-smoothing: antialiased;
        }
        .card {
            width: 100%; max-width: 440px; padding: 36px; background: #fff;
            border: 1px solid var(--line); border-radius: 12px;
        }
        .brand {
            display: inline-flex; align-items: center; gap: 10px;
            margin-bottom: 24px; color: var(--text); text-decoration: none;
            font-family: var(--serif); font-weight: 700; font-size: 22px;
        }
        h1 { margin: 0 0 8px; font-family: var(--serif); font-size: 28px; line-height: 1.2; }
        .muted { margin: 0; color: var(--muted); font-size: 14px; line-height: 1.6; }
        label { display: block; margin: 18px 0 6px; font-size: 13px; font-weight: 600; color: var(--text); }
        input[type="email"], input[type="password"], input[type="text"] {
            width: 100%; padding: 11px 13px; border: 1px solid var(--line);
            border-radius: 8px; font-size: 15px; color: var(--text); background: #fff;
        }
        input:focus-visible { outline: 2px solid var(--highlight); outline-offset: 1px; border-color: var(--highlight); }
        button[type="submit"] {
            width: 100%; margin-top: 24px; padding: 12px 16px; border: 0;
            border-radius: 8px; background: var(--primary); color: #fff; font-weight: 600;
            font-size: 15px; cursor: pointer; transition: background-color .15s;
        }
        button[type="submit"]:hover { background: var(--primary-hover); }
        button[type="submit"]:focus-visible { outline: 3px solid var(--highlight); outline-offset: 2px; }
        a { color: var(--primary); text-decoration: none; font-weight: 600; }
        a:hover { text-decoration: underline; }
        .footer { margin-top: 22px; padding-top: 18px; border-top: 1px solid var(--line); text-align: center; font-size: 14px; color: var(--muted); }
        .error { margin-top: 6px; color: #b42318; font-size: 13px; }
        .alert-error { margin-top: 14px; padding: 10px 12px; border-radius: 8px; background: #fef2f2; border: 1px solid #fecaca; color: #b42318; font-size: 13px; }
    </style>
</head>
<body>
    <main class="card">
        <a class="brand" href="{{ route('home') }}" aria-label="Nalar — kembali ke beranda">
            <svg width="28" height="28" viewBox="0 0 32 32" aria-hidden="true">
                <rect width="32" height="32" rx="7" fill="#EA580C"/>
                <path d="M9 24V8l14 16V8" fill="none" stroke="#FFFCF8" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                <circle cx="23" cy="8" r="2.2" fill="#FB923C"/>
            </svg>
            Nalar
        </a>
        <h1>Buat akun</h1>
        <p class="muted">Daftar untuk mulai belajar bersama Nalar.</p>

        @if ($errors->any())
            <div class="alert-error" role="alert">Periksa kembali data pendaftaranmu.</div>
        @endif

        <form method="POST" action="{{ route('register.store') }}">
            @csrf

            <label for="name">Nama lengkap</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}"
                autocomplete="name" required autofocus>
            @error('name') <div class="error">{{ $message }}</div> @enderror

            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                autocomplete="email" required>
            @error('email') <div class="error">{{ $message }}</div> @enderror

            <label for="password">Password</label>
            <input id="password" type="password" name="password"
                autocomplete="new-password" required>
            @error('password') <div class="error">{{ $message }}</div> @enderror

            <label for="password_confirmation">Konfirmasi password</label>
            <input id="password_confirmation" type="password"
                name="password_confirmation" autocomplete="new-password" required>

            <button type="submit">Buat akun</button>
        </form>

        <div class="footer">
            Sudah punya akun? <a href="{{ route('login') }}">Masuk</a>
        </div>
    </main>
</body>
</html>
