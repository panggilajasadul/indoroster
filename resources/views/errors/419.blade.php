<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sesi Berakhir – Indoroster</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: #0f1117;
            color: #e2e8f0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* Animated background */
        body::before {
            content: '';
            position: fixed;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(ellipse at 60% 40%, rgba(249, 115, 22, 0.08) 0%, transparent 60%),
                        radial-gradient(ellipse at 20% 80%, rgba(249, 115, 22, 0.05) 0%, transparent 50%);
            animation: bgPulse 6s ease-in-out infinite alternate;
            pointer-events: none;
        }

        @keyframes bgPulse {
            from { transform: scale(1); opacity: 0.8; }
            to { transform: scale(1.05); opacity: 1; }
        }

        .container {
            text-align: center;
            padding: 2rem;
            max-width: 480px;
            width: 100%;
            position: relative;
            z-index: 1;
        }

        .logo {
            margin-bottom: 2.5rem;
        }

        .logo img {
            height: 48px;
        }

        .logo-text {
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: 0.1em;
            color: #f97316;
        }

        .icon-wrapper {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(249, 115, 22, 0.12);
            border: 2px solid rgba(249, 115, 22, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            animation: iconPulse 2s ease-in-out infinite;
        }

        @keyframes iconPulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(249, 115, 22, 0.3); }
            50% { box-shadow: 0 0 0 12px rgba(249, 115, 22, 0); }
        }

        .icon-wrapper svg {
            width: 38px;
            height: 38px;
            color: #f97316;
        }

        h1 {
            font-size: 1.6rem;
            font-weight: 700;
            color: #f1f5f9;
            margin-bottom: 0.75rem;
        }

        p.desc {
            font-size: 0.95rem;
            color: #94a3b8;
            line-height: 1.7;
            margin-bottom: 2rem;
        }

        .card {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 1.25rem;
            padding: 1.5rem 1.75rem;
            margin-bottom: 1.5rem;
        }

        .countdown-label {
            font-size: 0.82rem;
            color: #64748b;
            margin-bottom: 0.5rem;
        }

        .countdown-bar-wrapper {
            height: 6px;
            background: rgba(255,255,255,0.08);
            border-radius: 999px;
            overflow: hidden;
            margin-bottom: 0.75rem;
        }

        .countdown-bar {
            height: 100%;
            background: linear-gradient(90deg, #f97316, #fb923c);
            border-radius: 999px;
            width: 100%;
            transition: width 1s linear;
        }

        .countdown-text {
            font-size: 0.88rem;
            color: #f97316;
            font-weight: 600;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 0.85rem 1.5rem;
            border-radius: 999px;
            font-family: 'Outfit', sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: #fff;
            box-shadow: 0 4px 20px rgba(249, 115, 22, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(249, 115, 22, 0.5);
        }

        .btn-secondary {
            background: transparent;
            color: #94a3b8;
            border: 1px solid rgba(255,255,255,0.12);
            margin-top: 0.75rem;
        }

        .btn-secondary:hover {
            background: rgba(255,255,255,0.05);
            color: #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <span class="logo-text">INDOROSTER</span>
        </div>

        <div class="icon-wrapper">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
            </svg>
        </div>

        <h1>Sesi Anda Sudah Berakhir</h1>
        <p class="desc">
            Halaman ini tidak aktif terlalu lama sehingga sesi keamanannya kedaluwarsa.<br>
            Tenang, data Anda aman — halaman akan dimuat ulang secara otomatis.
        </p>

        <div class="card">
            <p class="countdown-label">Memuat ulang otomatis dalam...</p>
            <div class="countdown-bar-wrapper">
                <div class="countdown-bar" id="progressBar"></div>
            </div>
            <p class="countdown-text" id="countdownText">5 detik</p>
        </div>

        <button class="btn btn-primary" onclick="refreshPage()">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            Muat Ulang Sekarang
        </button>

        <a href="/" class="btn btn-secondary">
            ← Kembali ke Beranda
        </a>
    </div>

    <script>
        const totalSeconds = 5;
        let remaining = totalSeconds;
        const bar = document.getElementById('progressBar');
        const text = document.getElementById('countdownText');

        function refreshPage() {
            // Kembali ke halaman sebelumnya dengan token baru
            window.location.href = document.referrer || '/';
        }

        const interval = setInterval(() => {
            remaining--;
            const pct = (remaining / totalSeconds) * 100;
            bar.style.width = pct + '%';
            text.textContent = remaining + ' detik';

            if (remaining <= 0) {
                clearInterval(interval);
                refreshPage();
            }
        }, 1000);
    </script>
</body>
</html>
