<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'HERO Reservation System')</title>
    <link rel="icon" href="{{ asset(config('hero.branding.favicon')) }}" type="image/png">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Instrument Sans', system-ui, sans-serif; background: #f8fafc; color: #0f172a; min-height: 100vh; }
        .nav {
            display: flex; justify-content: space-between; align-items: center;
            padding: 1rem 2rem; background: #0f172a; color: #fff; width: 100%;
        }
        .nav-brand {
            display: inline-flex;
            align-items: center;
            text-decoration: none;
            line-height: 0;
        }
        .nav-brand:hover { opacity: 0.9; }
        .nav-logo {
            display: block;
            height: 42px;
            width: auto;
            max-width: min(220px, 55vw);
        }
        .nav-links { display: flex; gap: 1rem; align-items: center; }
        .nav-links a:not(.btn) { color: #f59e0b; text-decoration: none; font-weight: 600; }
        .nav-links .btn-primary { background: #f59e0b; color: #0f172a !important; }
        main { width: 100%; }
        main.main-contained .page-body { max-width: 960px; margin: 0 auto; padding: 2rem 1.5rem; }
        .container { width: 100%; max-width: 1280px; margin: 0 auto; padding: 0 2rem; }
        .page-hero {
            position: relative;
            width: 100%;
            padding: 4rem 2rem;
            text-align: center;
            background: #0f172a center / cover no-repeat;
            color: #fff;
            overflow: hidden;
        }
        .page-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(15, 23, 42, 0.72) 0%, rgba(15, 23, 42, 0.88) 100%);
            pointer-events: none;
        }
        .page-hero > * { position: relative; z-index: 1; }
        .hero-logo {
            display: block;
            height: clamp(52px, 8vw, 72px);
            width: auto;
            margin: 0 auto 1rem;
        }
        .page-hero h1 { font-size: clamp(1.8rem, 4vw, 2.8rem); margin-bottom: 0.75rem; line-height: 1.15; }
        .page-hero-lead {
            max-width: 720px; margin: 0 auto 1.5rem;
            color: #cbd5e1; line-height: 1.65; font-size: 1.05rem;
        }
        .hero-actions { display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap; margin-bottom: 2rem; }
        .btn-outline { border: 1px solid #f59e0b; color: #f59e0b; background: transparent; }
        .page-hero .search-form {
            max-width: 1100px; margin: 0 auto; text-align: left;
            background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12);
            border-radius: 0.75rem; padding: 1.5rem;
        }
        .search-form-label { font-weight: 600; margin-bottom: 1rem; color: #fff; }
        .search-form label { color: #e2e8f0; }
        .search-grid {
            display: grid; grid-template-columns: 1fr 1fr 1fr auto;
            gap: 1rem; align-items: end;
        }
        .search-grid label { margin-bottom: 0.35rem; display: block; font-weight: 500; font-size: 0.875rem; }
        .search-grid input, .search-grid select { margin-bottom: 0; background: #fff; width: 100%; padding: 0.65rem; border: 1px solid #cbd5e1; border-radius: 0.5rem; }
        .search-grid .flatpickr-input,
        .search-grid input.flatpickr-input { margin-bottom: 0; background: #fff; cursor: pointer; color: #0f172a; }
        .search-grid .flatpickr-input:focus { outline: 2px solid #f59e0b; border-color: #f59e0b; }

        /* Flatpickr — HERO navy + amber theme (calendar appends to body) */
        .hero-flatpickr.flatpickr-calendar {
            font-family: 'Instrument Sans', system-ui, sans-serif;
            border-radius: 0.75rem;
            overflow: hidden;
            border: 1px solid #334155;
            box-shadow: 0 20px 50px rgba(15, 23, 42, 0.28);
            background: #fff;
        }
        .hero-flatpickr .flatpickr-months {
            background: linear-gradient(135deg, #0f172a, #334155);
            padding: 0.75rem 0.5rem;
        }
        .hero-flatpickr .flatpickr-months .flatpickr-month { color: #fff; height: 40px; }
        .hero-flatpickr .flatpickr-current-month {
            color: #fff;
            font-weight: 600;
            font-size: 1rem;
        }
        .hero-flatpickr .flatpickr-current-month .flatpickr-monthDropdown-months,
        .hero-flatpickr .flatpickr-current-month input.cur-year {
            color: #fff;
            font-weight: 600;
            background: transparent;
        }
        .hero-flatpickr .flatpickr-current-month .flatpickr-monthDropdown-months option {
            color: #0f172a;
            background: #fff;
        }
        .hero-flatpickr .flatpickr-months .flatpickr-prev-month,
        .hero-flatpickr .flatpickr-months .flatpickr-next-month {
            fill: #f59e0b;
            color: #f59e0b;
        }
        .hero-flatpickr .flatpickr-months .flatpickr-prev-month:hover svg,
        .hero-flatpickr .flatpickr-months .flatpickr-next-month:hover svg { fill: #fbbf24; }
        .hero-flatpickr .flatpickr-weekdays {
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }
        .hero-flatpickr span.flatpickr-weekday {
            color: #64748b;
            font-weight: 600;
            font-size: 0.8rem;
        }
        .hero-flatpickr .flatpickr-days { border: none; }
        .hero-flatpickr .flatpickr-day {
            color: #0f172a;
            border-radius: 0.5rem;
            border: 1px solid transparent;
            font-weight: 500;
        }
        .hero-flatpickr .flatpickr-day:hover,
        .hero-flatpickr .flatpickr-day:focus {
            background: #fef3c7;
            border-color: #fde68a;
            color: #0f172a;
        }
        .hero-flatpickr .flatpickr-day.today {
            border-color: #f59e0b;
            color: #0f172a;
        }
        .hero-flatpickr .flatpickr-day.today:hover {
            background: #fef3c7;
            color: #0f172a;
        }
        .hero-flatpickr .flatpickr-day.selected,
        .hero-flatpickr .flatpickr-day.startRange,
        .hero-flatpickr .flatpickr-day.endRange,
        .hero-flatpickr .flatpickr-day.selected.inRange,
        .hero-flatpickr .flatpickr-day.startRange.inRange,
        .hero-flatpickr .flatpickr-day.endRange.inRange {
            background: #f59e0b;
            border-color: #f59e0b;
            color: #0f172a;
            font-weight: 700;
            box-shadow: none;
        }
        .hero-flatpickr .flatpickr-day.selected:hover,
        .hero-flatpickr .flatpickr-day.startRange:hover,
        .hero-flatpickr .flatpickr-day.endRange:hover {
            background: #fbbf24;
            border-color: #fbbf24;
            color: #0f172a;
        }
        .hero-flatpickr .flatpickr-day.flatpickr-disabled,
        .hero-flatpickr .flatpickr-day.prevMonthDay,
        .hero-flatpickr .flatpickr-day.nextMonthDay {
            color: #cbd5e1;
        }
        .hero-flatpickr .flatpickr-time {
            border-top: 1px solid #e2e8f0;
            background: #f8fafc;
        }
        .hero-flatpickr .flatpickr-time .numInputWrapper span.arrowUp:after { border-bottom-color: #f59e0b; }
        .hero-flatpickr .flatpickr-time .numInputWrapper span.arrowDown:after { border-top-color: #f59e0b; }
        .hero-flatpickr .flatpickr-time input {
            color: #0f172a;
            font-weight: 600;
            background: #fff;
            border-radius: 0.375rem;
        }
        .hero-flatpickr .flatpickr-time input:hover,
        .hero-flatpickr .flatpickr-time input:focus {
            background: #fffbeb;
        }
        .hero-flatpickr .flatpickr-time .flatpickr-time-separator,
        .hero-flatpickr .flatpickr-time .flatpickr-am-pm {
            color: #64748b;
            font-weight: 600;
        }
        .hero-flatpickr .flatpickr-am-pm:hover,
        .hero-flatpickr .flatpickr-am-pm:focus {
            background: #fef3c7;
            color: #0f172a;
        }
        .search-actions { display: flex; gap: 0.5rem; flex-wrap: wrap; }
        .btn-outline-light { background: transparent; border: 1px solid rgba(255,255,255,0.35); color: #fff; }
        .section { width: 100%; padding: 3rem 0; }
        .section-light { background: #f8fafc; }
        .section-white { background: #fff; border-top: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0; }
        .section h2 { text-align: center; margin-bottom: 1.5rem; font-size: 1.5rem; }
        .section-intro { text-align: center; max-width: 640px; margin: -0.75rem auto 1.75rem; }
        .info-grid, .channels-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1rem;
        }
        .info-card, .channel-card {
            background: #fff; border: 1px solid #e2e8f0; border-radius: 0.75rem; padding: 1.35rem;
        }
        .section-white .info-card, .section-white .channel-card { background: #f8fafc; }
        .info-card h3, .channel-card h3 { margin-bottom: 0.5rem; font-size: 1rem; }
        .info-card p, .channel-card p { color: #64748b; font-size: 0.9rem; line-height: 1.55; }
        .results-list { display: grid; gap: 1rem; }
        .flight-card-main {
            display: flex; justify-content: space-between; align-items: center;
            gap: 1rem; flex-wrap: wrap;
        }
        .flight-card h3 { margin-bottom: 0.35rem; }
        .flight-card-price {
            display: flex; flex-direction: column; align-items: flex-end;
            gap: 0.5rem; text-align: right;
        }
        .flight-card-price strong { font-size: 1.25rem; }

        /* Flight search results */
        .results-panel {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            padding: 1.75rem;
            box-shadow: 0 8px 30px rgba(15, 23, 42, 0.06);
        }
        .results-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 1.5rem;
            padding-bottom: 1.25rem;
            border-bottom: 1px solid #e2e8f0;
        }
        .results-header-text h2 {
            text-align: left;
            margin-bottom: 0.35rem;
            font-size: 1.5rem;
        }
        .results-header-text p { margin: 0; max-width: none; text-align: left; }
        .results-count {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            background: linear-gradient(135deg, #0f172a, #334155);
            color: #fff;
            padding: 0.5rem 0.9rem;
            border-radius: 999px;
            font-size: 0.875rem;
            font-weight: 600;
            white-space: nowrap;
        }
        .results-count span { color: #f59e0b; font-weight: 700; }
        .search-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1.25rem;
        }
        .filter-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.4rem 0.75rem;
            border-radius: 999px;
            background: #fffbeb;
            border: 1px solid #fde68a;
            color: #92400e;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .results-empty {
            text-align: center;
            padding: 3rem 1.5rem;
            border-radius: 0.875rem;
            background: linear-gradient(180deg, #f8fafc 0%, #fff 100%);
            border: 1px dashed #cbd5e1;
        }
        .results-empty-icon {
            width: 72px;
            height: 72px;
            margin: 0 auto 1.25rem;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, #0f172a, #334155);
            color: #f59e0b;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.15);
        }
        .results-empty h3 {
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
            color: #0f172a;
        }
        .results-empty p {
            max-width: 480px;
            margin: 0 auto 1.5rem;
            color: #64748b;
            line-height: 1.6;
        }
        .results-empty-actions {
            display: flex;
            gap: 0.75rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        .flight-result-card {
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 1.25rem;
            align-items: center;
            padding: 1.25rem 1.35rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.875rem;
            background: #fff;
            transition: border-color 0.2s, box-shadow 0.2s, transform 0.2s;
        }
        .flight-result-card:hover {
            border-color: #f59e0b;
            box-shadow: 0 12px 32px rgba(245, 158, 11, 0.12);
            transform: translateY(-1px);
        }
        .flight-route-badge {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.35rem;
            min-width: 88px;
            padding: 0.75rem 0.5rem;
            border-radius: 0.75rem;
            background: linear-gradient(135deg, #0f172a, #334155);
            color: #fff;
            text-align: center;
        }
        .flight-route-badge .code {
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: 0.04em;
        }
        .flight-route-badge .arrow {
            color: #f59e0b;
            font-size: 0.9rem;
            line-height: 1;
        }
        .flight-result-body h3 {
            font-size: 1.1rem;
            margin-bottom: 0.65rem;
            color: #0f172a;
        }
        .flight-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        .flight-meta-item {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.35rem 0.65rem;
            border-radius: 999px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #475569;
            font-size: 0.8rem;
            font-weight: 500;
        }
        .flight-meta-item.seats-low {
            background: #fffbeb;
            border-color: #fde68a;
            color: #92400e;
        }
        .flight-result-action {
            text-align: right;
            min-width: 140px;
        }
        .flight-price-tag {
            display: block;
            font-size: 1.5rem;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.1;
            margin-bottom: 0.15rem;
        }
        .flight-price-tag small {
            display: block;
            font-size: 0.75rem;
            font-weight: 500;
            color: #64748b;
        }
        .flight-result-action .btn { width: 100%; margin-top: 0.75rem; }
        @media (max-width: 768px) {
            .flight-result-card {
                grid-template-columns: 1fr;
                text-align: left;
            }
            .flight-route-badge {
                flex-direction: row;
                justify-content: center;
                min-width: 0;
            }
            .flight-result-action {
                text-align: left;
                min-width: 0;
            }
            .flight-result-action .btn { width: auto; }
            .results-header { flex-direction: column; }
        }
        .split-block {
            display: grid; grid-template-columns: 1.2fr 1fr; gap: 2rem; align-items: start;
        }
        .split-block h2 { text-align: left; font-size: 1.35rem; margin-bottom: 0.75rem; }
        .highlight-box {
            background: #fffbeb; border: 1px solid #fde68a; border-radius: 0.75rem; padding: 1.35rem;
        }
        .highlight-box h3 { margin-bottom: 0.5rem; }
        .highlight-box p { color: #78350f; font-size: 0.9rem; line-height: 1.55; }
        .highlight-box .muted { color: #92400e !important; }
        .trust-bar {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem; text-align: center;
        }
        .trust-bar strong { display: block; font-size: 1.5rem; color: #0f172a; margin-bottom: 0.25rem; }
        .trust-bar span { color: #64748b; font-size: 0.875rem; }
        .featured-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.25rem;
        }
        .featured-card {
            background: #fff; border: 1px solid #e2e8f0; border-radius: 0.75rem;
            overflow: hidden; display: flex; flex-direction: column;
        }
        .featured-card.is-new { border-color: #f59e0b; box-shadow: 0 4px 24px rgba(245, 158, 11, 0.12); }
        .featured-badge {
            background: #f59e0b; color: #0f172a; font-size: 0.75rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.03em; padding: 0.5rem 1rem;
        }
        .featured-body { padding: 1.35rem; flex: 1; display: flex; flex-direction: column; }
        .featured-body h3 { font-size: 1.15rem; margin-bottom: 0.5rem; }
        .featured-price { font-size: 1.35rem; font-weight: 700; color: #0f172a; margin: 0.75rem 0; }
        .featured-price span { font-size: 0.875rem; font-weight: 500; color: #64748b; }
        .featured-actions { margin-top: auto; padding-top: 1rem; }
        .medevac-banner {
            display: grid; grid-template-columns: 1.2fr 1fr; gap: 2rem; align-items: center;
            background: linear-gradient(135deg, #0f172a, #1e293b); color: #fff;
            border-radius: 0.75rem; padding: 2rem; overflow: hidden;
        }
        .medevac-banner-image {
            width: 100%;
            height: 100%;
            min-height: 220px;
            object-fit: cover;
            border-radius: 0.5rem;
            border: 1px solid rgba(255,255,255,0.12);
        }
        .mission-banner {
            position: relative;
            border-radius: 0.75rem;
            overflow: hidden;
            min-height: 280px;
            display: grid;
            place-items: center;
            text-align: center;
            padding: 3rem 2rem;
            color: #fff;
            background: #0f172a center / cover no-repeat;
        }
        .mission-banner::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(15, 23, 42, 0.62);
        }
        .mission-banner > * { position: relative; z-index: 1; }
        .mission-banner h2 { color: #fff; margin-bottom: 0.75rem; font-size: clamp(1.35rem, 3vw, 1.75rem); }
        .mission-banner p { max-width: 720px; margin: 0 auto; color: #e2e8f0; line-height: 1.65; }
        .footer-logo {
            display: block;
            height: 48px;
            width: auto;
            margin: 0 auto 1rem;
            opacity: 0.95;
        }
        .medevac-banner h2 { text-align: left; color: #fff; margin-bottom: 0.75rem; font-size: 1.35rem; }
        .medevac-banner p { color: #cbd5e1; line-height: 1.6; }
        .contact-block { text-align: center; }
        .contact-block p { margin-bottom: 0.35rem; }
        .contact-block a { color: #f59e0b; font-weight: 600; text-decoration: none; }
        .site-footer a { color: #f59e0b; text-decoration: none; }
        .site-footer {
            width: 100%; text-align: center; padding: 2rem;
            font-size: 0.875rem; background: #0f172a; color: #94a3b8;
        }
        .alert-wrap { max-width: 1280px; margin: 0 auto; padding: 1rem 2rem 0; }
        .alert { padding: 0.85rem 1rem; border-radius: 0.5rem; margin-bottom: 0; }
        .alert-error { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
        .alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
        .btn {
            display: inline-block; padding: 0.65rem 1.2rem; border-radius: 0.5rem;
            font-weight: 600; text-decoration: none; border: none; cursor: pointer; font-size: 0.9rem;
        }
        .btn-primary { background: #f59e0b; color: #0f172a; }
        .btn-secondary { background: #e2e8f0; color: #0f172a; }
        .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 0.75rem; padding: 1.25rem; }
        .muted { color: #64748b; font-size: 0.9rem; }
        label { display: block; font-weight: 500; margin-bottom: 0.35rem; font-size: 0.875rem; }
        input, select { width: 100%; padding: 0.65rem; border: 1px solid #cbd5e1; border-radius: 0.5rem; margin-bottom: 1rem; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        @media (max-width: 768px) {
            .search-grid { grid-template-columns: 1fr; }
            .flight-card-price { align-items: flex-start; text-align: left; }
            .split-block { grid-template-columns: 1fr; }
            .nav { padding: 1rem 1.25rem; }
            .container { padding: 0 1.25rem; }
            .page-hero { padding: 3rem 1.25rem; }
            .medevac-banner { grid-template-columns: 1fr; }
        }
        @media (max-width: 640px) { .grid-2 { grid-template-columns: 1fr; } }
    </style>
    @stack('styles')
</head>
<body>
    <header class="nav">
        <a href="{{ route('home') }}" class="nav-brand">
            <img src="{{ asset(config('hero.branding.logo')) }}" alt="HERO Client Rescue" class="nav-logo">
        </a>
        <div class="nav-links">
            <a href="{{ route('home') }}#flights">Book a Flight</a>
            <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
        </div>
    </header>

    @if(session('error') || session('success'))
        <div class="alert-wrap">
            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
        </div>
    @endif

    <main class="@yield('main_class', 'main-contained')">
        <div class="@yield('main_wrapper', 'page-body')">
            @yield('content')
        </div>
    </main>
    @stack('scripts')
</body>
</html>
