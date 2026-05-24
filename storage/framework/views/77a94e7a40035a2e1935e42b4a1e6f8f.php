<?php if (isset($component)) { $__componentOriginalaa758e6a82983efcbf593f765e026bd9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalaa758e6a82983efcbf593f765e026bd9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => $__env->getContainer()->make(Illuminate\View\Factory::class)->make('mail::message'),'data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('mail::message'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<div style="text-align: left; display: table; width: 100%; border-bottom: 2px solid #f3f4f6; padding-bottom: 20px; margin-bottom: 20px;">
    <div style="display: table-cell; vertical-align: middle; width: 50%;">
        <img src="<?php echo new \Illuminate\Support\EncodedHtmlString($message->embed(public_path('assets/logo_indoroster-text.png'))); ?>" alt="Indoroster Logo" style="max-height: 80px; width: auto;">
    </div>
    <div style="display: table-cell; vertical-align: middle; width: 50%; text-align: right;">
        <div style="margin-bottom: 8px; font-size: 16px; font-weight: bold; color: #1f2937;">Status Pesanan</div>
        <div style="text-align: right;">
            <div style="display: inline-block; color: #ea580c; font-weight: 900; font-size: 20px; border: 3px solid #ea580c; padding: 4px 16px; border-radius: 6px; letter-spacing: 2px; transform: rotate(-5deg); margin-top: 5px;">
                LUNAS
            </div>
        </div>
    </div>
</div>

# Halo, <?php echo new \Illuminate\Support\EncodedHtmlString($order->shipping_name); ?>


<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($statusType === 'processing'): ?>
Pesanan Anda dengan nomor **<?php echo new \Illuminate\Support\EncodedHtmlString($order->order_number); ?>** sedang kami siapkan / produksi. Estimasi penyiapan pesanan adalah maksimal 3 hari kerja tergantung antrean pesanan. Kami akan mengabari Anda kembali jika pesanan sudah siap dikirim.
<?php elseif($statusType === 'shipped'): ?>
Kabar gembira! Pesanan Anda dengan nomor **<?php echo new \Illuminate\Support\EncodedHtmlString($order->order_number); ?>** telah selesai diproses dan saat ini sudah diserahkan ke pihak logistik untuk dikirim ke alamat Anda.

<?php if (isset($component)) { $__componentOriginal91214b38020aa1d764d4a21e693f703c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal91214b38020aa1d764d4a21e693f703c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => $__env->getContainer()->make(Illuminate\View\Factory::class)->make('mail::panel'),'data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('mail::panel'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
### 📦 Detail Pengiriman
- **Kurir / Ekspedisi:** <?php echo new \Illuminate\Support\EncodedHtmlString($order->courier ?? 'Armada Pabrik Indoroster'); ?>

- **Nomor Resi / Plat:** <?php echo new \Illuminate\Support\EncodedHtmlString($order->tracking_number ?? '-'); ?>

- **No. WA Kurir (Sopir):** <?php echo new \Illuminate\Support\EncodedHtmlString($order->courier_phone ?? '-'); ?>

- **Estimasi Sampai:** 2-4 Hari Kerja
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal91214b38020aa1d764d4a21e693f703c)): ?>
<?php $attributes = $__attributesOriginal91214b38020aa1d764d4a21e693f703c; ?>
<?php unset($__attributesOriginal91214b38020aa1d764d4a21e693f703c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal91214b38020aa1d764d4a21e693f703c)): ?>
<?php $component = $__componentOriginal91214b38020aa1d764d4a21e693f703c; ?>
<?php unset($__componentOriginal91214b38020aa1d764d4a21e693f703c); ?>
<?php endif; ?>

<?php elseif($statusType === 'completed'): ?>
Terima kasih telah berbelanja kebutuhan material bangunan di Indoroster! Pesanan Anda dengan nomor **<?php echo new \Illuminate\Support\EncodedHtmlString($order->order_number); ?>** telah ditandai selesai. Kami harap Anda puas dengan produk dan layanan kami.

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->delivery_photo_path): ?>
<?php if (isset($component)) { $__componentOriginal91214b38020aa1d764d4a21e693f703c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal91214b38020aa1d764d4a21e693f703c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => $__env->getContainer()->make(Illuminate\View\Factory::class)->make('mail::panel'),'data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('mail::panel'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
### 📸 Bukti Pengiriman  
<img src="<?php echo new \Illuminate\Support\EncodedHtmlString($message->embed(storage_path('app/public/' . $order->delivery_photo_path))); ?>" alt="Bukti Pengiriman" style="max-width: 100%; border-radius: 8px;">
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal91214b38020aa1d764d4a21e693f703c)): ?>
<?php $attributes = $__attributesOriginal91214b38020aa1d764d4a21e693f703c; ?>
<?php unset($__attributesOriginal91214b38020aa1d764d4a21e693f703c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal91214b38020aa1d764d4a21e693f703c)): ?>
<?php $component = $__componentOriginal91214b38020aa1d764d4a21e693f703c; ?>
<?php unset($__componentOriginal91214b38020aa1d764d4a21e693f703c); ?>
<?php endif; ?>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

---

### 🛒 Ringkasan Pesanan
<div style="font-size: 13px; color: #6b7280; margin-bottom: 10px;">
<em>*Hanya sebagai rincian dokumentasi. Pesanan ini <strong>Telah Lunas</strong> dibayar sebelumnya. Anda tidak perlu membayar apapun kepada kurir (kecuali pengiriman belum termasuk).</em>
</div>

<?php if (isset($component)) { $__componentOriginal85530901ee91af5decf39e8ed3495cde = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal85530901ee91af5decf39e8ed3495cde = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => $__env->getContainer()->make(Illuminate\View\Factory::class)->make('mail::table'),'data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('mail::table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
| Produk | Qty | Harga | Subtotal |
|:-------|:---:|:-----:|:--------:|
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
| <?php echo new \Illuminate\Support\EncodedHtmlString($item->product_name); ?> <?php echo new \Illuminate\Support\EncodedHtmlString($item->product_variant_name ? '('.$item->product_variant_name.')' : ''); ?> | <?php echo new \Illuminate\Support\EncodedHtmlString($item->quantity); ?> | Rp<?php echo new \Illuminate\Support\EncodedHtmlString(number_format($item->product_price, 0, ',', '.')); ?> | Rp<?php echo new \Illuminate\Support\EncodedHtmlString(number_format($item->subtotal, 0, ',', '.')); ?> |
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
| | | | |
| **Subtotal** | | | **Rp<?php echo new \Illuminate\Support\EncodedHtmlString(number_format($order->subtotal, 0, ',', '.')); ?>** |
| **Ongkos Kirim** | | | **Rp<?php echo new \Illuminate\Support\EncodedHtmlString(number_format($order->shipping_cost, 0, ',', '.')); ?>** |
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->discount_amount > 0): ?>
| **Diskon** | | | **-Rp<?php echo new \Illuminate\Support\EncodedHtmlString(number_format($order->discount_amount, 0, ',', '.')); ?>** |
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
| **Total Akhir (LUNAS)** | | | **Rp<?php echo new \Illuminate\Support\EncodedHtmlString(number_format($order->grand_total, 0, ',', '.')); ?>** |
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal85530901ee91af5decf39e8ed3495cde)): ?>
<?php $attributes = $__attributesOriginal85530901ee91af5decf39e8ed3495cde; ?>
<?php unset($__attributesOriginal85530901ee91af5decf39e8ed3495cde); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal85530901ee91af5decf39e8ed3495cde)): ?>
<?php $component = $__componentOriginal85530901ee91af5decf39e8ed3495cde; ?>
<?php unset($__componentOriginal85530901ee91af5decf39e8ed3495cde); ?>
<?php endif; ?>

---

### 📍 Alamat Tujuan
**<?php echo new \Illuminate\Support\EncodedHtmlString($order->shipping_name); ?>**  
<?php echo new \Illuminate\Support\EncodedHtmlString($order->shipping_address); ?>  
<?php echo new \Illuminate\Support\EncodedHtmlString($order->shipping_city); ?>, <?php echo new \Illuminate\Support\EncodedHtmlString($order->shipping_province); ?> <?php echo new \Illuminate\Support\EncodedHtmlString($order->shipping_postal_code); ?>  
No. HP: <?php echo new \Illuminate\Support\EncodedHtmlString($order->shipping_phone); ?>


<?php if (isset($component)) { $__componentOriginal15a5e11357468b3880ae1300c3be6c4f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal15a5e11357468b3880ae1300c3be6c4f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => $__env->getContainer()->make(Illuminate\View\Factory::class)->make('mail::button'),'data' => ['url' => config('app.url') . '/member/pesanan/' . $order->order_number]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('mail::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(config('app.url') . '/member/pesanan/' . $order->order_number)]); ?>
Lacak Pesanan Saya
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal15a5e11357468b3880ae1300c3be6c4f)): ?>
<?php $attributes = $__attributesOriginal15a5e11357468b3880ae1300c3be6c4f; ?>
<?php unset($__attributesOriginal15a5e11357468b3880ae1300c3be6c4f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal15a5e11357468b3880ae1300c3be6c4f)): ?>
<?php $component = $__componentOriginal15a5e11357468b3880ae1300c3be6c4f; ?>
<?php unset($__componentOriginal15a5e11357468b3880ae1300c3be6c4f); ?>
<?php endif; ?>

Terima kasih atas kepercayaannya.  
Jika ada pertanyaan, jangan ragu untuk membalas email ini.

Salam Hangat,<br>
**Tim <?php echo new \Illuminate\Support\EncodedHtmlString(config('app.name')); ?>**
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalaa758e6a82983efcbf593f765e026bd9)): ?>
<?php $attributes = $__attributesOriginalaa758e6a82983efcbf593f765e026bd9; ?>
<?php unset($__attributesOriginalaa758e6a82983efcbf593f765e026bd9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalaa758e6a82983efcbf593f765e026bd9)): ?>
<?php $component = $__componentOriginalaa758e6a82983efcbf593f765e026bd9; ?>
<?php unset($__componentOriginalaa758e6a82983efcbf593f765e026bd9); ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\indoroster\resources\views/emails/orders/status.blade.php ENDPATH**/ ?>