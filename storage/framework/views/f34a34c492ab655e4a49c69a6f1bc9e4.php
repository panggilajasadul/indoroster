<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Jalan - <?php echo e($order->order_number); ?></title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; color: #333; line-height: 1.5; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; position: relative; z-index: 1; }
        .watermark {
            position: absolute;
            top: 20%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100px;
            font-weight: bold;
            color: rgba(220, 38, 38, 0.15); /* Faint red */
            z-index: 99;
            pointer-events: none;
            white-space: nowrap;
        }
        .header { width: 100%; display: table; margin-bottom: 20px; }
        .header td { vertical-align: top; }
        .header .logo { font-size: 28px; font-weight: bold; color: #2d3748; }
        .header .company-info { text-align: right; color: #718096; font-size: 12px; max-width: 300px; }
        .title { font-size: 22px; font-weight: bold; text-align: center; margin: 20px 0; text-transform: uppercase; color: #4a5568; border-bottom: 2px solid #c2410c; padding-bottom: 10px; }
        .details { width: 100%; display: table; margin-bottom: 30px; }
        .details-col { display: table-cell; width: 50%; }
        .details-label { font-weight: bold; font-size: 11px; color: #718096; text-transform: uppercase; margin-bottom: 5px; }
        table.items { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        table.items th, table.items td { padding: 10px; border-bottom: 1px solid #e2e8f0; text-align: left; }
        table.items th { background: #f7fafc; font-weight: bold; color: #4a5568; }
        table.items td.right, table.items th.right { text-align: right; }
        .summary { width: 100%; display: table; }
        .summary-col { display: table-cell; width: 60%; }
        .summary-totals { display: table-cell; width: 40%; }
        table.totals { width: 100%; border-collapse: collapse; }
        table.totals td { padding: 8px 10px; text-align: right; }
        table.totals tr.bold td { font-weight: bold; font-size: 16px; border-top: 2px solid #c2410c; color: #c2410c; }
        .notes { margin-top: 40px; font-size: 12px; color: #718096; border-top: 1px solid #e2e8f0; padding-top: 10px; }
        .status-badge { display: inline-block; padding: 3px 8px; border-radius: 3px; font-size: 11px; font-weight: bold; }
        .status-primary { background: #ebf8ff; color: #2b6cb0; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->payment_status === 'paid'): ?>
        <div class="watermark">LUNAS</div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <table class="header">
            <tr>
                <td class="logo-cell">
                    <img src="<?php echo e(public_path('assets/logo_indoroster-text.png')); ?>" style="max-height: 120px;">
                </td>
                <td class="company-info">
                    <strong style="color: #c2410c; font-size: 16px;">indoroster.com</strong><br>
                    <span style="font-size: 11px;">Pabrik Roster & bata ekpose dan ornamen dinding Terlengkap</span><br>
                    Kp. Cicadas, RT 05 RW 03, Desa Cadasmekar,<br>
                    Kec. Tegalwaru, Kab. Purwakarta, Jawa Barat, 41165<br>
                    WhatsApp: <?php echo e(\App\Models\SiteSetting::getValue('whatsapp_number', '0813-8970-9847')); ?>

                </td>
            </tr>
        </table>

        <div class="title">SURAT JALAN</div>

        <div class="details">
            <div class="details-col">
                <div class="details-label">Data Penerima:</div>
                <strong style="font-size: 16px;"><?php echo e($order->shipping_name); ?></strong><br>
                <?php echo e($order->shipping_address); ?><br>
                <?php echo e($order->shipping_city); ?>, <?php echo e($order->shipping_province); ?> <?php echo e($order->shipping_postal_code); ?><br>
                <strong>HP: <?php echo e($order->shipping_phone); ?></strong>
            </div>
            <div class="details-col" style="padding-left: 20px;">
                <div class="details-label">Informasi Pesanan:</div>
                <table style="width: 100%; font-size: 13px;">
                    <tr><td width="40%">No. Pesanan</td><td>: <strong><?php echo e($order->order_number); ?></strong></td></tr>
                    <tr><td>Tanggal</td><td>: <?php echo e($order->created_at->format('d M Y H:i')); ?></td></tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->courier): ?>
                    <tr><td>Kurir</td><td>: <?php echo e($order->courier); ?></td></tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->courier_phone): ?>
                    <tr><td>No. WA Kurir</td><td>: <?php echo e($order->courier_phone); ?></td></tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->tracking_number): ?>
                    <tr><td>No. Resi/Plat</td><td>: <?php echo e($order->tracking_number); ?></td></tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <tr><td>Status</td><td>: <span class="status-badge status-primary">
                        <?php echo e(match($order->status) {
                            'pending' => 'MENUNGGU',
                            'processing' => 'DIPROSES',
                            'shipped' => 'DIKIRIM',
                            'delivered' => 'DITERIMA',
                            'completed' => 'SELESAI',
                            'cancelled' => 'DIBATALKAN',
                            default => strtoupper($order->status)
                        }); ?>

                    </span></td></tr>
                    <tr><td>Pembayaran</td><td>: 
                        <?php echo e(match($order->payment_status) {
                            'unpaid' => 'BELUM BAYAR',
                            'paid' => 'LUNAS',
                            'expired' => 'KADALUWARSA',
                            'failed' => 'GAGAL',
                            default => strtoupper($order->payment_status)
                        }); ?>

                    </td></tr>
                </table>
            </div>
        </div>

        <table class="items">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th class="right">Harga Satuan</th>
                    <th class="right">Qty</th>
                    <th class="right">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>
                        <strong><?php echo e($item->product_name); ?></strong><br>
                        <small style="color: #718096;">Varian: <?php echo e($item->product_variant_name ?: '-'); ?></small>
                    </td>
                    <td class="right">Rp <?php echo e(number_format($item->product_price, 0, ',', '.')); ?></td>
                    <td class="right"><?php echo e($item->quantity); ?></td>
                    <td class="right">Rp <?php echo e(number_format($item->subtotal, 0, ',', '.')); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>

        <div class="summary">
            <div class="summary-col">
                <div class="notes">
                    <strong>Catatan Pembeli:</strong><br>
                    <?php echo e($order->notes ?: 'Tidak ada catatan.'); ?>

                </div>
                <div style="margin-top: 20px; font-size: 11px;">
                    Dicetak pada: <?php echo e(now()->format('d/m/Y H:i')); ?>

                </div>
            </div>
            <div class="summary-totals">
                <table class="totals">
                    <tr>
                        <td>Subtotal</td>
                        <td>Rp <?php echo e(number_format($order->subtotal, 0, ',', '.')); ?></td>
                    </tr>
                    <tr>
                        <td>Ongkos Kirim</td>
                        <td>Rp <?php echo e(number_format($order->shipping_cost, 0, ',', '.')); ?></td>
                    </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->discount_amount > 0): ?>
                    <tr>
                        <td>Diskon</td>
                        <td style="color: red;">- Rp <?php echo e(number_format($order->discount_amount, 0, ',', '.')); ?></td>
                    </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <tr class="bold">
                        <td>TOTAL AKHIR</td>
                        <td>Rp <?php echo e(number_format($order->grand_total, 0, ',', '.')); ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <div style="margin-top: 50px; width: 100%; display: table;">
            <div style="display: table-cell; text-align: center; width: 50%;">
                Penerima,<br><br><br><br>
                ( ............................ )
            </div>
            <div style="display: table-cell; text-align: center; width: 50%;">
                Hormat Kami,<br><br><br><br>
                ( ............................ )
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\indoroster\resources\views/print/order.blade.php ENDPATH**/ ?>