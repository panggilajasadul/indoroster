<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        \App\Models\SiteSetting::updateOrCreate(
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
        \App\Models\SiteSetting::where('key', 'navbar_alignment')->delete();
    }
};
