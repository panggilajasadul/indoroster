<?php

use App\Models\SiteSetting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        SiteSetting::updateOrCreate(
            ['key' => 'navbar_alignment'],
            [
                'group' => 'general',
                'key' => 'navbar_alignment',
                'value' => 'left',
                'type' => 'text',
                'description' => 'Posisi menu navigasi di header (pilih salah satu: left, center, atau right)',
            ]
        );
    }

    public function down(): void
    {
        SiteSetting::where('key', 'navbar_alignment')->delete();
    }
};
