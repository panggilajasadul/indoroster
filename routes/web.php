<?php

use App\Http\Controllers\PrintController;
use App\Http\Controllers\SitemapController;
use App\Livewire\AboutUs;
use App\Livewire\Application\ApplicationDetail;
use App\Livewire\Application\ApplicationHub;
use App\Livewire\ArticleDetail;
use App\Livewire\ArticleList;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Auth\VerifyEmail;
use App\Livewire\B2b\ArchitectHub;
use App\Livewire\B2b\ContractorHub;
use App\Livewire\B2b\DeveloperHub;
use App\Livewire\B2b\ProjectHub;
use App\Livewire\B2b\SupplierHub;
use App\Livewire\Cart;
use App\Livewire\Checkout;
use App\Livewire\Contact;
use App\Livewire\DynamicPage;
use App\Livewire\Export\ExportCatalog;
use App\Livewire\Export\ExportCountry;
use App\Livewire\Export\ExportGallery;
use App\Livewire\Export\ExportHub;
use App\Livewire\Gallery;
use App\Livewire\Home;
use App\Livewire\Location\LocationDetail;
use App\Livewire\Location\LocationHub;
use App\Livewire\Member\AddressBook;
use App\Livewire\Member\Notifications;
use App\Livewire\Member\OrderHistory;
use App\Livewire\Member\Profile;
use App\Livewire\OrderSuccess;
use App\Livewire\OrderTracking;
use App\Livewire\ProductCatalog;
use App\Livewire\ProductDetail;
use App\Livewire\ProductionProcess;
use App\Livewire\Seo\SeoPageDetailFallback;
use App\Livewire\Tools\RosterCalculator;
use App\Livewire\VideoInspiration;
use App\Mail\InvoiceMail;
use App\Models\Order;
use App\Notifications\CompleteAddressNotification;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;

// Sitemap Master Index & Sub-Sitemaps
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/sitemap.xsl', [SitemapController::class, 'xsl'])->name('sitemap.xsl');
Route::get('/sitemap-pages.xml', [SitemapController::class, 'pages'])->name('sitemap.pages');
Route::get('/sitemap-applications.xml', [SitemapController::class, 'applications'])->name('sitemap.applications');
Route::get('/sitemap-exports.xml', [SitemapController::class, 'exports'])->name('sitemap.exports');
Route::get('/sitemap-seopages.xml', [SitemapController::class, 'seopages'])->name('sitemap.seopages');
Route::get('/sitemap-categories.xml', [SitemapController::class, 'categories'])->name('sitemap.categories');
Route::get('/sitemap-products.xml', [SitemapController::class, 'products'])->name('sitemap.products');
Route::get('/sitemap-locations.xml', [SitemapController::class, 'locations'])->name('sitemap.locations');
Route::get('/sitemap-articles.xml', [SitemapController::class, 'articles'])->name('sitemap.articles');
Route::get('/sitemap-galleries.xml', [SitemapController::class, 'galleries'])->name('sitemap.galleries');

// Frontend Routes (publik, bisa diakses siapa saja)
Route::get('/', Home::class)->name('home');
Route::get('/katalog', ProductCatalog::class)->name('catalog');
Route::redirect('/produk', '/katalog', 301);
Route::get('/katalog/{categorySlug}', ProductCatalog::class)->name('catalog.category');
Route::get('/produk/{slug}', ProductDetail::class)->name('product.detail');
Route::get('/keranjang', Cart::class)->name('cart');

// Artikel & Blog CMS Routes
Route::redirect('/blog', '/artikel', 301);
Route::get('/blog/{slug}', function ($slug) {
    return redirect('/artikel/'.$slug, 301);
});
Route::get('/artikel', ArticleList::class)->name('article.index');
Route::get('/artikel/{slug}', ArticleDetail::class)->name('article.detail');

// Authentication & Member Routes
Route::get('/lacak-pesanan', OrderTracking::class)->name('order.tracking');

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
    Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
    Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
});

// Checkout: boleh guest, tapi kalau login harus terverifikasi
Route::middleware(['verified.if.auth'])->group(function () {
    Route::get('/checkout', Checkout::class)->name('checkout');
    Route::get('/checkout/success', OrderSuccess::class)->name('checkout.success');
});

// Route member: wajib login
Route::middleware(['auth'])->group(function () {
    Route::get('/member/profil', Profile::class)->name('member.profile');
    Route::get('/member/alamat', AddressBook::class)->name('member.addresses');
    Route::get('/member/pesanan', OrderHistory::class)->name('member.orders');
    Route::get('/member/notifikasi', Notifications::class)->name('member.notifications');
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
    Route::get('/email/verify', VerifyEmail::class)->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();

        // Kirim Notifikasi Lonceng agar melengkapi alamat
        $request->user()->notify(new CompleteAddressNotification);

        return redirect()->route('member.addresses')->with('success', 'Email berhasil diverifikasi! Silakan lengkapi alamat Anda.');
    })->middleware(['signed'])->name('verification.verify');
});

Route::get('/gallery', Gallery::class)->name('gallery');
Route::get('/gallery/{slug}', Gallery::class)->name('gallery.show');
Route::get('/video-inspirasi', VideoInspiration::class)->name('video-inspiration');
Route::get('/video-inspirasi/{slug}', VideoInspiration::class)->name('video-inspiration.show');
Route::get('/proses-produksi', ProductionProcess::class)->name('production');
Route::get('/tentang-kami', AboutUs::class)->name('about-us');
Route::get('/kontak', Contact::class)->name('contact');

// B2B & Project Procurement Hub Routes
Route::get('/untuk-kontraktor', ContractorHub::class)->name('b2b.contractor');
Route::get('/untuk-developer', DeveloperHub::class)->name('b2b.developer');
Route::get('/untuk-arsitek', ArchitectHub::class)->name('b2b.architect');
Route::get('/supplier-roster-beton', SupplierHub::class)->name('b2b.supplier');
Route::get('/roster-beton-grosir', SupplierHub::class)->name('b2b.wholesale');
Route::get('/roster-beton-proyek', ProjectHub::class)->name('b2b.project');

// Interactive Tools & Calculation Engine
Route::get('/kalkulator-roster', RosterCalculator::class)->name('tools.calculator');

// Architectural Use-Case Landing Pages
Route::get('/aplikasi', ApplicationHub::class)->name('application.index');
Route::get('/aplikasi/{slug}', ApplicationDetail::class)->name('application.detail');

// Scalable Multi-City Location Engine Routes
Route::get('/lokasi', LocationHub::class)->name('location.index');
Route::get('/lokasi/{slug}', LocationDetail::class)->name('location.detail');

// International Export Gateway (Singapore, Malaysia, Brunei)
Route::get('/export', ExportHub::class)->name('export.hub');
Route::get('/export/gallery', ExportGallery::class)->name('export.gallery');
Route::get('/export/catalog', ExportCatalog::class)->name('export.catalog');
Route::get('/export/{countrySlug}', ExportCountry::class)->name('export.country');

// Dynamic Pages (/page/{slug} as requested, /halaman/{slug} redirects with 301)
Route::get('/page/{slug}', DynamicPage::class)->name('dynamic.page');
Route::get('/halaman/{slug}', function ($slug) {
    return redirect()->route('dynamic.page', $slug, 301);
});

Route::get('/checkout/pending', function () {
    return redirect('/')->with('success', 'Pesanan dibuat! Silakan selesaikan pembayaran Anda.');
});

// Route untuk Preview Email Invoice (Hanya untuk Testing)
if (app()->environment('local')) {
    Route::get('/preview-email', function () {
        $order = Order::latest()->first();
        if (! $order) {
            return 'Belum ada pesanan untuk dipreview.';
        }

        return new InvoiceMail($order);
    });
}

// Invoice & Order Print Routes (secure authorization checked in controller)
Route::get('/print/invoice/{invoice}', [PrintController::class, 'invoice'])->name('print.invoice');
Route::get('/print/receipt/{payment}', [PrintController::class, 'receipt'])->name('print.receipt');
Route::get('/print/order/{order}', [PrintController::class, 'order'])->name('print.order');

// Print Routes for Admin Only
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/print/shipping-label/{shippingLabel}', [PrintController::class, 'shippingLabel'])->name('print.shipping-label');
    Route::get('/print/manual-document/{document}', [PrintController::class, 'manualDocument'])->name('print.manual-document');
    Route::get('/print/template-test/{template}', [PrintController::class, 'templateTest'])->name('print.template-test');
});

// SEO Page Factory — Prioritas: SeoPage published > DynamicPage CMS
// SeoPageDetailFallback menangani dispatch tanpa redirect loop
Route::get('/{slug}', SeoPageDetailFallback::class)->where('slug', '^[a-zA-Z0-9\-_]+$')->name('dynamic.page.direct');
