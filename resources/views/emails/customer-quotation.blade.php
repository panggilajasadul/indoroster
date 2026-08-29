<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subjectText }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f8fafc; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #1e293b; line-height: 1.6;">
    <div style="max-width: 600px; margin: 30px auto; background: #ffffff; border-radius: 16px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
        
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%); padding: 32px 24px; text-align: center; color: #ffffff;">
            <h1 style="margin: 0; font-size: 24px; font-weight: 800; letter-spacing: 2px; text-transform: uppercase;">INDOROSTER</h1>
            <p style="margin: 6px 0 0 0; font-size: 13px; color: #ffedd5; letter-spacing: 0.5px;">Pabrik Produsen Roster Beton & Bata Expose Arsitektural</p>
        </div>

        <!-- Body Content -->
        <div style="padding: 32px 24px;">
            <p style="font-size: 16px; font-weight: 600; margin-top: 0; color: #0f172a;">
                Yth. {{ $user->name }}{{ $user->company_name ? ' (' . $user->company_name . ')' : '' }},
            </p>

            @if($offerTitle)
                <div style="background: #fff7ed; border-left: 4px solid #ea580c; padding: 14px 18px; border-radius: 8px; margin: 20px 0;">
                    <strong style="color: #9a3412; font-size: 15px;">{{ $offerTitle }}</strong>
                </div>
            @endif

            <div style="font-size: 14px; color: #334155; line-height: 1.8; margin: 20px 0; white-space: pre-line;">
{!! nl2br(e($messageBody)) !!}
            </div>

            <!-- Hubungi Sales / Konsultasi Button -->
            @php
                $waNumber = config('services.whatsapp.number', '6281234567890');
                $waUrl = "https://wa.me/{$waNumber}?text=" . urlencode("Halo Tim IndoRoster, saya menindaklanjuti penawaran email terkait: {$subjectText}");
            @endphp
            <div style="text-align: center; margin: 32px 0 16px 0;">
                <a href="{{ $waUrl }}" style="display: inline-block; background-color: #ea580c; color: #ffffff; text-decoration: none; font-size: 14px; font-weight: 700; padding: 12px 28px; border-radius: 10px; box-shadow: 0 4px 10px rgba(234, 88, 12, 0.25);">
                    Hubungi Tim Sales Proyek (WhatsApp)
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div style="background: #f1f5f9; padding: 20px 24px; text-align: center; font-size: 12px; color: #64748b; border-top: 1px solid #e2e8f0;">
            <p style="margin: 0 0 4px 0; font-weight: 700; color: #0f172a;">PABRIK INDOROSTER INDONESIA</p>
            <p style="margin: 0 0 8px 0;">Kp. Cicadas, Desa Cadasmekar, Purwakarta, Jawa Barat | Website: <a href="https://indoroster.com" style="color: #ea580c; text-decoration: none;">indoroster.com</a></p>
            <p style="margin: 0; font-size: 11px; color: #94a3b8;">Email ini dikirimkan resmi kepada Anda sebagai mitra/pelanggan terdaftar IndoRoster.</p>
        </div>

    </div>
</body>
</html>
