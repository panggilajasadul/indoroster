<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice Pesanan <?php echo e($order->order_number); ?></title>
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
            text-align: center;
            border-bottom: 2px solid #f3f4f6;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #c2410c;
            /* terra-700 */
        }

        .invoice-title {
            font-size: 20px;
            color: #111827;
            margin-top: 10px;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            background-color: #def7ec;
            color: #03543f;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            margin-top: 10px;
        }

        .details-grid {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .details-col {
            width: 48%;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #374151;
            margin-bottom: 10px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th,
        td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        th {
            background-color: #f9fafb;
            font-weight: bold;
            color: #4b5563;
            font-size: 14px;
        }

        td {
            font-size: 14px;
            color: #1f2937;
        }

        .text-right {
            text-align: right;
        }

        .font-bold {
            font-weight: bold;
        }

        .text-terra {
            color: #c2410c;
        }

        .summary-row td {
            border-bottom: none;
            padding: 8px 15px;
        }

        .summary-row.total td {
            font-size: 18px;
            font-weight: bold;
            border-top: 2px solid #e5e7eb;
            padding-top: 15px;
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
        <div class="header" style="text-align: left; display: table; width: 100%; border-bottom: 2px solid #f3f4f6; padding-bottom: 20px; margin-bottom: 20px;">
            <div style="display: table-cell; vertical-align: middle; width: 40%;">
                <img src="<?php echo e($message->embed(public_path('assets/logo_indoroster-text.png'))); ?>" alt="Indoroster Logo" style="max-height: 120px; width: auto;">
            </div>
            <div style="display: table-cell; vertical-align: bottom; width: 60%; text-align: right;">
                <div class="invoice-title" style="margin-bottom: 10px; font-size: 18px; font-weight: bold; color: #1f2937;">Pembayaran Berhasil!</div>
                <div style="text-align: right;">
                    <div style="display: inline-block; background-color: #d1fae5; color: #065f46; padding: 8px 15px; border-radius: 9999px; font-weight: bold; font-size: 16px;">
                        <span style="margin-right: 5px;">✔</span> LUNAS
                    </div>
                </div>
            </div>
        </div>

        <div style="margin-bottom: 20px; font-size: 14px;">
            Halo <strong><?php echo e($order->shipping_name); ?></strong>,<br>
            Terima kasih atas pesanan Anda. Pembayaran untuk pesanan <strong><?php echo e($order->order_number); ?></strong> telah
            kami terima. Berikut adalah rincian pesanan Anda:
        </div>

        <div class="details-grid">
            <div class="details-col">
                <div class="section-title">Detail Pesanan</div>
                <strong>Nomor Order:</strong> <?php echo e($order->order_number); ?><br>
                <strong>Tanggal Order:</strong> <?php echo e($order->created_at->format('d M Y, H:i')); ?><br>
                <strong>Tanggal Bayar:</strong>
                <?php echo e($order->paid_at ? $order->paid_at->format('d M Y, H:i') : now()->format('d M Y, H:i')); ?><br>
                <strong>Metode Pembayaran:</strong>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->latestPayment): ?>
                    <?php echo e($order->latestPayment->payment_type_label); ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->latestPayment->va_number): ?>
                        (VA: <?php echo e($order->latestPayment->va_number); ?>)
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php else: ?>
                    Midtrans
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div class="details-col">
                <div class="section-title">Alamat Pengiriman</div>
                <strong><?php echo e($order->shipping_name); ?></strong><br>
                <?php echo e($order->shipping_phone); ?><br>
                <?php echo e($order->shipping_address); ?><br>
                <?php echo e($order->shipping_city); ?>, <?php echo e($order->shipping_province); ?> <?php echo e($order->shipping_postal_code); ?>

            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Harga</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td>
                            <?php echo e($item->product_name); ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->product_variant_name && $item->product_variant_name !== '-'): ?>
                                <br><small style="color: #6b7280;">Varian: <?php echo e($item->product_variant_name); ?></small>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                        <td class="text-right"><?php echo e($item->quantity); ?></td>
                        <td class="text-right">Rp<?php echo e(number_format($item->product_price, 0, ',', '.')); ?></td>
                        <td class="text-right">Rp<?php echo e(number_format($item->subtotal, 0, ',', '.')); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <tr class="summary-row">
                    <td colspan="3" class="text-right"><strong>Subtotal</strong></td>
                    <td class="text-right">Rp<?php echo e(number_format($order->subtotal, 0, ',', '.')); ?></td>
                </tr>
                <tr class="summary-row">
                    <td colspan="3" class="text-right"><strong>Ongkos Kirim</strong></td>
                    <td class="text-right">Rp<?php echo e(number_format($order->shipping_cost, 0, ',', '.')); ?></td>
                </tr>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->discount_amount > 0): ?>
                    <tr class="summary-row">
                        <td colspan="3" class="text-right"><strong>Diskon</strong></td>
                        <td class="text-right text-terra">-Rp<?php echo e(number_format($order->discount_amount, 0, ',', '.')); ?></td>
                    </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <tr class="summary-row total">
                    <td colspan="3" class="text-right">Total Keseluruhan</td>
                    <td class="text-right text-terra">Rp<?php echo e(number_format($order->grand_total, 0, ',', '.')); ?></td>
                </tr>
            </tbody>
        </table>

        <div
            style="font-size: 14px; background: #fdf2f0; border-left: 4px solid #c2410c; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
            <strong>Catatan Penting:</strong><br>
            Pesanan Anda sedang kami siapkan untuk dikirim. Estimasi pesanan diproses paling lambat tanggal <strong><?php echo e($order->created_at->copy()->addDays(3)->translatedFormat('d F Y')); ?></strong> (jika tidak bentrok dengan pesanan lain akan lebih cepat diproses).<br><br>
            Estimasi pesanan sampai di lokasi Anda antara tanggal <strong><?php echo e($order->created_at->copy()->addDays(5)->translatedFormat('d F Y')); ?></strong> hingga paling lambat <strong><?php echo e($order->created_at->copy()->addDays(7)->translatedFormat('d F Y')); ?></strong>.
        </div>

        <div class="footer">
            Ini adalah email konfirmasi pembayaran otomatis dari <strong>Indoroster</strong>.<br>
            © <?php echo e(date('Y')); ?> <?php echo e(config('app.name')); ?>. All rights reserved.<br>
            <?php echo e(\App\Models\SiteSetting::getValue('factory_address', 'Plered, Purwakarta, Jawa Barat')); ?>

        </div>
    </div>
</body>

</html><?php /**PATH C:\xampp\htdocs\indoroster\resources\views/emails/invoice.blade.php ENDPATH**/ ?>