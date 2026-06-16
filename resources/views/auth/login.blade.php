<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login — HERO Reservation System</title>
    <link rel="icon" href="{{ asset(config('hero.branding.favicon')) }}" type="image/png">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Instrument Sans', system-ui, sans-serif;
            min-height: 100vh;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #451a03 100%);
            color: #f8fafc;
            padding: 1.5rem;
        }
        .card {
            width: 100%;
            max-width: 420px;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 1rem;
            padding: 2rem;
            backdrop-filter: blur(12px);
        }
        .brand { text-align: center; margin-bottom: 2rem; }
        .brand-logo {
            display: block;
            height: 56px;
            width: auto;
            margin: 0 auto 1rem;
        }
        .brand h1 { font-size: 1.35rem; font-weight: 700; }
        .brand h1 a { color: inherit; text-decoration: none; }
        .brand h1 a:hover { color: #f59e0b; }
        .brand p { color: #cbd5e1; font-size: 0.9rem; margin-top: 0.35rem; }
        label { display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.4rem; color: #e2e8f0; }
        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 0.75rem 0.9rem;
            border-radius: 0.6rem;
            border: 1px solid rgba(255,255,255,0.15);
            background: rgba(15,23,42,0.6);
            color: #fff;
            margin-bottom: 1rem;
        }
        input:focus { outline: 2px solid #f59e0b; border-color: transparent; }
        .remember { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.25rem; color: #cbd5e1; font-size: 0.875rem; }
        button {
            width: 100%;
            padding: 0.85rem;
            border: none;
            border-radius: 0.6rem;
            background: #f59e0b;
            color: #0f172a;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
        }
        button:hover { background: #fbbf24; }
        .error { background: #7f1d1d; color: #fecaca; padding: 0.75rem; border-radius: 0.5rem; margin-bottom: 1rem; font-size: 0.875rem; }
        .back { display: block; text-align: center; margin-top: 1.25rem; color: #94a3b8; font-size: 0.875rem; text-decoration: none; }
        .back:hover { color: #f59e0b; }
    </style>
</head>
<body>
    <div class="card">
        <div class="brand">
            <a href="{{ route('home') }}">
                <img src="{{ asset(config('hero.branding.logo')) }}" alt="HERO Client Rescue" class="brand-logo">
            </a>
            <h1><a href="{{ route('home') }}">Reservation System</a></h1>
            <p>Sign in to your portal</p>
        </div>

        @if ($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login.store') }}">
            @csrf
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>

            <label for="password">Password</label>
            <input id="password" type="password" name="password" required>

            <label class="remember">
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                Remember me
            </label>

            <button type="submit">Sign In</button>
        </form>

        <a class="back" href="{{ route('home') }}">&larr; Back to home</a>
    </div>
</body>
</html>
