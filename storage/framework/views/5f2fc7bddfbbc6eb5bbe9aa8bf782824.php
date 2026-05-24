<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice Faktur - <?php echo e($invoice->invoice_number); ?></title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; color: #333; line-height: 1.5; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); }
        .header { width: 100%; display: table; margin-bottom: 20px; }
        .header td { vertical-align: top; }
        .header .logo { font-size: 28px; font-weight: bold; color: #2d3748; }
        .header .company-info { text-align: right; color: #718096; font-size: 12px; max-width: 300px; }
        .title { font-size: 24px; font-weight: bold; text-align: center; margin: 20px 0; text-transform: uppercase; color: #4a5568; }
        .details { width: 100%; display: table; margin-bottom: 30px; }
        .details-col { display: table-cell; width: 50%; }
        .details-label { font-weight: bold; font-size: 12px; color: #718096; text-transform: uppercase; margin-bottom: 5px; }
        table.items { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        table.items th, table.items td { padding: 10px; border-bottom: 1px solid #e2e8f0; text-align: left; }
        table.items th { background: #f7fafc; font-weight: bold; color: #4a5568; }
        table.items td.right, table.items th.right { text-align: right; }
        .summary { width: 100%; display: table; }
        .summary-col { display: table-cell; width: 60%; }
        .summary-totals { display: table-cell; width: 40%; }
        table.totals { width: 100%; border-collapse: collapse; }
        table.totals td { padding: 8px 10px; text-align: right; }
        table.totals tr.bold td { font-weight: bold; font-size: 16px; border-top: 2px solid #cbd5e0; }
        .notes { margin-top: 40px; font-size: 12px; color: #718096; border-top: 1px solid #e2e8f0; padding-top: 10px; }
        .status { display: inline-block; padding: 4px 10px; border-radius: 4px; font-weight: bold; font-size: 12px; text-transform: uppercase; }
        .status-paid { background: #fdf2f0; color: #c2410c; border: 1px solid #c2410c; }
        .status-unpaid { background: #fed7d7; color: #742a2a; }
        tr.bold td { color: #c2410c; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <table class="header">
            <tr>
                <td class="logo">
                    <img src="<?php echo e(public_path('assets/logo_indoroster-text.png')); ?>" alt="Indoroster Logo" style="max-height: 120px;">
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

        <div class="title">INVOICE FAKTUR</div>

        <div class="details">
            <div class="details-col">
                <div class="details-label">Ditagihkan Kepada:</div>
                <strong><?php echo e($invoice->order->shipping_name); ?></strong><br>
                <?php echo e($invoice->order->shipping_address); ?><br>
                <?php echo e($invoice->order->shipping_city); ?>, <?php echo e($invoice->order->shipping_province); ?> <?php echo e($invoice->order->shipping_postal_code); ?><br>
                HP: <?php echo e($invoice->order->shipping_phone); ?>

            </div>
            <div class="details-col" style="padding-left: 20px;">
                <div class="details-label">Informasi Invoice:</div>
                <table style="width: 100%; font-size: 14px;">
                    <tr><td width="40%">No. Invoice</td><td>: <strong><?php echo e($invoice->invoice_number); ?></strong></td></tr>
                    <tr><td>Tanggal</td><td>: <?php echo e($invoice->invoice_date->format('d M Y')); ?></td></tr>
                    <tr><td>No. Pesanan</td><td>: <?php echo e($invoice->order->order_number); ?></td></tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($invoice->order->latestPayment): ?>
                    <tr>
                        <td>Metode Bayar</td>
                        <td>: <?php echo e($invoice->order->latestPayment->payment_type_label); ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($invoice->order->latestPayment->va_number): ?>
                                (VA: <?php echo e($invoice->order->latestPayment->va_number); ?>)
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                    </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <tr>
                        <td>Status</td>
                        <td>: 
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($invoice->status === 'paid'): ?>
                                <span class="status status-paid">LUNAS</span>
                            <?php else: ?>
                                <span class="status status-unpaid"><?php echo e(strtoupper($invoice->status_label)); ?></span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <table class="items">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th class="right">Harga</th>
                    <th class="right">Qty</th>
                    <th class="right">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $invoice->order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>
                        <strong><?php echo e($item->product_name); ?></strong><br>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->variant): ?>
                            <small style="color: #718096;">Varian: <?php echo e($item->variant->name); ?></small><br>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <small style="color: #718096;"><?php echo e($item->product->material ?? ''); ?> - <?php echo e($item->product->dimensions ?? ''); ?></small>
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
                    <strong>Catatan:</strong><br>
                    <?php echo e($invoice->notes ?: 'Terima kasih telah berbelanja di indoroster.com. Barang yang sudah dibeli tidak dapat ditukar atau dikembalikan.'); ?>

                </div>
            </div>
            <div class="summary-totals">
                <table class="totals">
                    <tr>
                        <td>Subtotal</td>
                        <td>Rp <?php echo e(number_format($invoice->subtotal, 0, ',', '.')); ?></td>
                    </tr>
                    <tr>
                        <td>Ongkos Kirim</td>
                        <td>Rp <?php echo e(number_format($invoice->shipping_cost, 0, ',', '.')); ?></td>
                    </tr>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($invoice->discount_amount > 0): ?>
                    <tr>
                        <td>Diskon</td>
                        <td style="color: red;">- Rp <?php echo e(number_format($invoice->discount_amount, 0, ',', '.')); ?></td>
                    </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($invoice->tax_amount > 0): ?>
                    <tr>
                        <td>Pajak</td>
                        <td>Rp <?php echo e(number_format($invoice->tax_amount, 0, ',', '.')); ?></td>
                    </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <tr class="bold">
                        <td>GRAND TOTAL</td>
                        <td>Rp <?php echo e(number_format($invoice->grand_total, 0, ',', '.')); ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\indoroster\resources\views/print/invoice.blade.php ENDPATH**/ ?>