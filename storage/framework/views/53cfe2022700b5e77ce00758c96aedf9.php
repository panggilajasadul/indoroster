<?php if (isset($component)) { $__componentOriginal166a02a7c5ef5a9331faf66fa665c256 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal166a02a7c5ef5a9331faf66fa665c256 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-panels::components.page.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-panels::page'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="space-y-6">
        <!-- Main Description -->
        <?php if (isset($component)) { $__componentOriginalee08b1367eba38734199cf7829b1d1e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalee08b1367eba38734199cf7829b1d1e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.section.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
             <?php $__env->slot('heading', null, []); ?> 
                ⚙️ Kontrol Simulasi & Penjualan Produk
             <?php $__env->endSlot(); ?>
             <?php $__env->slot('description', null, []); ?> 
                Gunakan halaman ini untuk memantau produk yang baru diunggah, memfilter produk dengan tingkat penjualan rendah (di bawah 5.000 terjual), dan menyuntikkan jumlah penjualan fiktif (baik setel ulang secara langsung atau menambahkan ke jumlah terjual saat ini).
             <?php $__env->endSlot(); ?>
            
            <div class="text-xs text-gray-500 dark:text-gray-400 mt-2 flex flex-col gap-1 border-t border-gray-100 dark:border-gray-800 pt-3">
                <p>💡 <strong>Petunjuk Penggunaan:</strong></p>
                <ul class="list-disc pl-5 space-y-1">
                    <li>Gunakan tombol toggle filter <strong>"Terjual < 5.000"</strong> untuk menyaring produk dengan penjualan rendah.</li>
                    <li>Gunakan filter <strong>"Produk Baru (30 Hari Terakhir)"</strong> untuk memantau produk yang baru saja diupload.</li>
                    <li>Klik aksi <span class="text-emerald-600 font-semibold">Suntik Terjual</span> pada baris produk untuk merubah atau menambah data unit terjual.</li>
                    <li>Klik aksi <span class="text-amber-600 font-semibold">Ulasan Baru</span> untuk mengisi ulasan simulasi acak / bertarget bintang untuk produk terkait.</li>
                    <li>Anda juga dapat melakukan suntik penjualan secara massal dengan mencentang beberapa produk sekaligus dan menekan tombol <span class="font-bold">Suntik Terjual Massal</span> di bawah tabel.</li>
                </ul>
            </div>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalee08b1367eba38734199cf7829b1d1e9)): ?>
<?php $attributes = $__attributesOriginalee08b1367eba38734199cf7829b1d1e9; ?>
<?php unset($__attributesOriginalee08b1367eba38734199cf7829b1d1e9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalee08b1367eba38734199cf7829b1d1e9)): ?>
<?php $component = $__componentOriginalee08b1367eba38734199cf7829b1d1e9; ?>
<?php unset($__componentOriginalee08b1367eba38734199cf7829b1d1e9); ?>
<?php endif; ?>

        <!-- Product Table -->
        <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-xl p-4 shadow-sm">
            <?php echo e($this->table); ?>

        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal166a02a7c5ef5a9331faf66fa665c256)): ?>
<?php $attributes = $__attributesOriginal166a02a7c5ef5a9331faf66fa665c256; ?>
<?php unset($__attributesOriginal166a02a7c5ef5a9331faf66fa665c256); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal166a02a7c5ef5a9331faf66fa665c256)): ?>
<?php $component = $__componentOriginal166a02a7c5ef5a9331faf66fa665c256; ?>
<?php unset($__componentOriginal166a02a7c5ef5a9331faf66fa665c256); ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\indoroster\resources\views/filament/pages/product-simulation.blade.php ENDPATH**/ ?>