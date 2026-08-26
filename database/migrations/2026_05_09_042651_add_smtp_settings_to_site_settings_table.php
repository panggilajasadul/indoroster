<?php

use App\Models\SiteSetting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $settings = [
            [
                'group' => 'mail',
                'key' => 'mail_host',
                'value' => env('MAIL_HOST', 'smtp.gmail.com'),
                'type' => 'text',
                'description' => 'SMTP Host (e.g., smtp.gmail.com)',
            ],
            [
                'group' => 'mail',
                'key' => 'mail_port',
                'value' => env('MAIL_PORT', '587'),
                'type' => 'number',
                'description' => 'SMTP Port (e.g., 587 or 465)',
            ],
            [
                'group' => 'mail',
                'key' => 'mail_username',
                'value' => env('MAIL_USERNAME', ''),
                'type' => 'text',
                'description' => 'SMTP Username / Email',
            ],
            [
                'group' => 'mail',
                'key' => 'mail_password',
                'value' => env('MAIL_PASSWORD', ''),
                'type' => 'text',
                'description' => 'SMTP Password / App Password',
            ],
            [
                'group' => 'mail',
                'key' => 'mail_encryption',
                'value' => env('MAIL_ENCRYPTION', 'tls'),
                'type' => 'text',
                'description' => 'Encryption (tls/ssl)',
            ],
            [
                'group' => 'mail',
                'key' => 'mail_from_address',
                'value' => env('MAIL_FROM_ADDRESS', 'noreply@indoroster.com'),
                'type' => 'text',
                'description' => 'Email Pengirim',
            ],
            [
                'group' => 'mail',
                'key' => 'mail_from_name',
                'value' => env('MAIL_FROM_NAME', 'Indoroster'),
                'type' => 'text',
                'description' => 'Nama Pengirim',
            ],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        SiteSetting::where('group', 'mail')->delete();
    }
};
