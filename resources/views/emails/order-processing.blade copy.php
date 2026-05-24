<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Pesanan Sedang Disiapkan {{ $order->order_number }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f9fafb;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .header {
            text-align: left;
            border-bottom: 2px solid #f3f4f6;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .status-title {
            font-size: 20px;
            color: #c2410c; /* terra-700 */
            font-weight: bold;
            margin-top: 10px;
        }

        .details-grid {
            margin-bottom: 30px;
            font-size: 14px;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #374151;
            margin-bottom: 10px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
        }

        .info-box {
            font-size: 14px;
            background: #fff7ed;
            border-left: 4px solid #f97316;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="{{ $message->embed(public_path('assets/logo_indoroster-text.png')) }}" alt="Indoroster Logo" style="max-height: 80px; width: auto;">
            <div class="status-title">Pesanan Sedang Disiapkan! 🔧</div>
        </div>

        <div style="margin-bottom: 20px; font-size: 14px;">
            Halo <strong>{{ $order->shipping_name }}</strong>,<br>
            Kabar baik! Tim kami sedang menyiapkan pesanan Anda dengan nomor order <strong>{{ $order->order_number }}</strong>. Kami akan memastikan setiap detail produk sesuai dengan standar kualitas terbaik kami sebelum dikirim.
        </div>

        <div class="info-box">
            <strong>Estimasi Penyiapan:</strong><br>
            Proses penyiapan (produksi/packing) memakan waktu maksimal <strong>3 hari kerja</strong> (tergantung antrean stok). Kami akan segera mengabari Anda jika pesanan sudah siap diberangkatkan.
        </div>

        <div class="details-grid">
            <div class="section-title">Detail Pengiriman</div>
            <strong>Penerima:</strong> {{ $order->shipping_name }}<br>
            <strong>Alamat:</strong> {{ $order->shipping_address }}, {{ $order->shipping_city }}, {{ $order->shipping_province }} {{ $order->shipping_postal_code }}
        </div>

        <div style="font-size: 14px;">
            Terima kasih telah mempercayakan kebutuhan bangunan Anda kepada <strong>Indoroster</strong>.
        </div>

        <div class="footer">
            Ini adalah email otomatis dari <strong>Indoroster</strong>.<br>
            © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.<br>
            {{ \App\Models\SiteSetting::getValue('factory_address', 'Plered, Purwakarta, Jawa Barat') }}
        </div>
    </div>
</body>

</html>
