<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taking Too Long — 2Dine-In</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            background: linear-gradient(135deg, #fdf7f2 0%, #f5e8d9 100%);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        .card {
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.12);
            max-width: 420px;
            width: 100%;
            padding: 40px;
            text-align: center;
            animation: fadeUp 0.45s ease both;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .icon-wrap {
            width: 80px; height: 80px;
            background: #fff7ed;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 24px;
        }

        .icon-wrap svg { width: 40px; height: 40px; stroke: #ea5a47; }

        h1 { font-size: 1.5rem; font-weight: 900; color: #1f2937; margin-bottom: 8px; }

        p  { font-size: 0.875rem; color: #6b7280; line-height: 1.6; margin-bottom: 24px; }

        .countdown-box {
            background: #fff7ed;
            border-radius: 16px;
            padding: 12px 16px;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            margin-bottom: 24px;
            font-size: 0.875rem; color: #374151;
        }

        .countdown-box svg { width: 16px; height: 16px; stroke: #ea5a47; flex-shrink: 0; }
        .countdown-box span { font-weight: 700; color: #ea5a47; }

        .btn {
            width: 100%; padding: 12px;
            border: none; border-radius: 16px;
            font-size: 0.9rem; font-weight: 700;
            cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;
            transition: opacity .2s, transform .1s;
            margin-bottom: 10px;
            text-decoration: none;
        }
        .btn:active { transform: scale(0.97); }
        .btn-primary { background: linear-gradient(135deg, #ea5a47, #c53030); color: #fff; }
        .btn-primary:hover { opacity: 0.9; }
        .btn-secondary { background: #f3f4f6; color: #4b5563; }
        .btn-secondary:hover { background: #e5e7eb; }
        .btn svg { width: 16px; height: 16px; }

        form { margin: 0; }
    </style>
</head>
<body>
    <div class="card">

        <div class="icon-wrap">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>

        <h1>An Error Has Occurred</h1>
        <p>
            The server took too long to respond and the request timed out.<br>
            Please go back or log out and try again.
        </p>

        <div class="countdown-box">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Going back in <span id="countdown">15</span>s
        </div>

        <button onclick="window.history.back()" class="btn btn-primary">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Go Back
        </button>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-secondary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Log Out
            </button>
        </form>

    </div>

    <script>
        let s = 15;
        const el = document.getElementById('countdown');
        const t  = setInterval(() => {
            el.textContent = --s;
            if (s <= 0) { clearInterval(t); window.history.back(); }
        }, 1000);
    </script>
</body>
</html>
