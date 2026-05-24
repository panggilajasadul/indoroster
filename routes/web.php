<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\SitemapController;
use App\Livewire\Home;
use App\Livewire\ProductCatalog;
use App\Livewire\ProductDetail;
use App\Livewire\Cart;
use App\Livewire\Checkout;
use App\Livewire\OrderSuccess;
use App\Livewire\DynamicPage;
use App\Livewire\AboutUs;
use App\Livewire\Contact;
use App\Livewire\ProductionProcess;
use App\Livewire\Gallery;
use App\Livewire\VideoInspiration;

// Sitemap
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Frontend Routes (publik, bisa diakses siapa saja)
Route::get('/', Home::class)->name('home');
Route::get('/katalog', ProductCatalog::class)->name('catalog');
Route::get('/produk/{slug}', ProductDetail::class)->name('product.detail');
Route::get('/keranjang', Cart::class)->name('cart');

// Authentication & Member Routes
Route::get('/lacak-pesanan', \App\Livewire\OrderTracking::class)->name('order.tracking');

Route::middleware('guest')->group(function () {
    Route::get('/login', \App\Livewire\Auth\Login::class)->name('login');
    Route::get('/register', \App\Livewire\Auth\Register::class)->name('register');
});

// Checkout: boleh guest, tapi kalau login harus terverifikasi
Route::middleware(['verified.if.auth'])->group(function () {
    Route::get('/checkout', Checkout::class)->name('checkout');
    Route::get('/checkout/success', OrderSuccess::class)->name('checkout.success');
});

// Route member: wajib login + email terverifikasi
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/member/alamat', \App\Livewire\Member\AddressBook::class)->name('member.addresses');
    Route::get('/member/pesanan', \App\Livewire\Member\OrderHistory::class)->name('member.orders');
    Route::get('/member/notifikasi', \App\Livewire\Member\Notifications::class)->name('member.notifications');
});

// Route yang butuh login saja (tanpa verifikasi)
Route::middleware('auth')->group(function () {
    Route::get('/logout', function () {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Anda berhasil keluar.');
    })->name('logout');
});

// Email Verification Routes
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', \App\Livewire\Auth\VerifyEmail::class)->name('verification.notice');
    
    Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
        $request->fulfill();
        
        // Kirim Notifikasi Lonceng agar melengkapi alamat
        $request->user()->notify(new \App\Notifications\CompleteAddressNotification());

        return redirect()->route('member.addresses')->with('success', 'Email berhasil diverifikasi! Silakan lengkapi alamat Anda.');
    })->middleware(['signed'])->name('verification.verify');
});

Route::get('/gallery', Gallery::class)->name('gallery');
Route::get('/video-inspirasi', VideoInspiration::class)->name('video-inspiration');
Route::get('/proses-produksi', ProductionProcess::class)->name('production');
Route::get('/tentang-kami', AboutUs::class)->name('about-us');
Route::get('/kontak', Contact::class)->name('contact');

Route::get('/halaman/{slug}', DynamicPage::class)->name('dynamic.page');

Route::get('/checkout/pending', function () {
    return redirect('/')->with('success', 'Pesanan dibuat! Silakan selesaikan pembayaran Anda.');
});

// Route untuk Preview Email Invoice (Hanya untuk Testing)
if (app()->environment('local')) {
    Route::get('/preview-email', function () {
        $order = \App\Models\Order::latest()->first();
        if (!$order) return "Belum ada pesanan untuk dipreview.";
        return new \App\Mail\InvoiceMail($order);
    });
}

// Invoice print route (secure authorization checked in controller)
Route::get('/print/invoice/{invoice}', [PrintController::class, 'invoice'])->name('print.invoice');

// Print Routes for Admin Only
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/print/order/{order}', [PrintController::class, 'order'])->name('print.order');
    Route::get('/print/shipping-label/{shippingLabel}', [PrintController::class, 'shippingLabel'])->name('print.shipping-label');
});
