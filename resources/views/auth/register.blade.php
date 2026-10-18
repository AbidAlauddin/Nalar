<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar | Nalar</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0; min-height: 100vh; display: grid; place-items: center;
            padding: 24px; background: #f4f6fb; color: #172033;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }
        .card { width: 100%; max-width: 440px; padding: 36px; background: white;
            border: 1px solid #e4e8f0; border-radius: 18px; }
        h1 { margin: 0 0 8px; font-size: 30px; }
        .muted { color: #697386; line-height: 1.6; }
        label { display: block; margin: 18px 0 8px; font-size: 14px; font-weight: 600; }
        input { width: 100%; padding: 12px 14px; border: 1px solid #d7dce5;
            border-radius: 9px; font-size: 15px; }
        input:focus { outline: 2px solid #9db8f8; border-color: #3159a8; }
        button { width: 100%; margin-top: 24px; padding: 13px; border: 0;
            border-radius: 9px; background: #173b79; color: white; font-weight: 600;
            font-size: 15px; cursor: pointer; }
        a { color: #214f9b; text-decoration: none; }
        .footer { margin-top: 22px; text-align: center; font-size: 14px; }
        .error { margin-top: 6px; color: #b42318; font-size: 13px; }
        .brand { margin-bottom: 26px; color: #173b79; font-weight: 800; letter-spacing: 1px; }
    </style>
</head>
<body>
    <main class="card">
        <div class="brand">NALAR</div>
        <h1>Buat akun</h1>
        <p class="muted">Daftar untuk mulai belajar bersama Nalar.</p>

        @if ($errors->any())
            <div class="error">Periksa kembali data pendaftaranmu.</div>
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
