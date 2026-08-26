<?php

namespace App\Providers;

use App\Http\Controllers\SitemapController;
use App\Models\Article;
use App\Models\Category;
use App\Models\Gallery;
use App\Models\Order;
use App\Models\Page;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Observers\OrderObserver;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\HtmlString;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Enforce HTTPS scheme in production environment
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // Dynamically override public storage URL based on the current request host/port
        // to prevent Filepond / file upload preview hangs on mismatched dev ports (e.g. 8000 vs 8001).
        if (! app()->runningInConsole() && request()) {
            config(['filesystems.disks.public.url' => request()->getSchemeAndHttpHost().'/storage']);
        }

        // Load SMTP settings from database if table exists
        try {
            if (Schema::hasTable('site_settings')) {
                $mailSettings = SiteSetting::where('group', 'mail')->pluck('value', 'key');

                if ($mailSettings->isNotEmpty()) {
                    config([
                        'mail.mailers.smtp.host' => $mailSettings->get('mail_host', config('mail.mailers.smtp.host')),
                        'mail.mailers.smtp.port' => $mailSettings->get('mail_port', config('mail.mailers.smtp.port')),
                        'mail.mailers.smtp.username' => $mailSettings->get('mail_username', config('mail.mailers.smtp.username')),
                        'mail.mailers.smtp.password' => $mailSettings->get('mail_password', config('mail.mailers.smtp.password')),
                        'mail.mailers.smtp.encryption' => $mailSettings->get('mail_encryption', config('mail.mailers.smtp.encryption')),
                        'mail.from.address' => $mailSettings->get('mail_from_address', config('mail.from.address')),
                        'mail.from.name' => $mailSettings->get('mail_from_name', config('mail.from.name')),
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Silently fail if DB not connected
        }

        // Register Observers
        Order::observe(OrderObserver::class);

        // Auto-generate sitemap on product, category, gallery, and page changes
        $sitemapGenerator = function () {
            try {
                SitemapController::generate();
            } catch (\Exception $e) {
                // Silently fail to not block admin save/delete operations
            }
        };

        Product::saved($sitemapGenerator);
        Product::deleted($sitemapGenerator);
        Category::saved($sitemapGenerator);
        Category::deleted($sitemapGenerator);
        Page::saved($sitemapGenerator);
        Page::deleted($sitemapGenerator);
        Gallery::saved($sitemapGenerator);
        Gallery::deleted($sitemapGenerator);
        Article::saved($sitemapGenerator);
        Article::deleted($sitemapGenerator);

        // Custom professional verification email narrative
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('Verifikasi Alamat Email Anda - Indoroster')
                ->greeting('Halo, '.$notifiable->name.'!')
                ->line('Terima kasih telah bergabung dengan Indoroster. Untuk memastikan keamanan akun Anda dan mengaktifkan seluruh layanan kami, kami mohon agar Anda memverifikasi alamat email Anda.')
                ->action('Verifikasi Email Saya', $url)
                ->line('Tautan verifikasi ini akan kedaluwarsa dalam waktu 60 menit.')
                ->line('Apabila Anda tidak merasa mendaftar di akun Indoroster, Anda tidak perlu melakukan tindakan apa pun dan dapat mengabaikan email ini.')
                ->salutation(new HtmlString('Salam hangat,<br><strong>Tim Indoroster</strong>'));
        });
    }
}
