<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Book a Flight — HERO Reservation System</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Instrument Sans', system-ui, sans-serif; background: #f8fafc; color: #0f172a; }
        .wrap { max-width: 720px; margin: 0 auto; padding: 3rem 1.5rem; }
        a { color: #b45309; }
        .notice {
            background: #fffbeb; border: 1px solid #fcd34d; border-radius: 0.75rem;
            padding: 1.5rem; line-height: 1.6;
        }
        h1 { margin-bottom: 1rem; }
        p { margin-bottom: 0.75rem; color: #475569; }
    </style>
</head>
<body>
    <div class="wrap">
        <p><a href="{{ route('home') }}">&larr; Back to home</a></p>
        <h1>Public Flight Booking</h1>
        <div class="notice">
            <p><strong>Coming in the next phase.</strong></p>
            <p>
                Your specification calls for a public booking page where passengers can see
                flight legs marked as <em>Public</em>, choose seats, and pay online.
                That customer-facing booking flow will connect to this page.
            </p>
            <p>
                Agency users and staff should use the unified login:
                <a href="{{ route('login') }}">{{ route('login') }}</a>
            </p>
        </div>
    </div>
</body>
</html>
