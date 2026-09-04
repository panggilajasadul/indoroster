<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Sinkronkan data lama yang status pembayarannya lunas (paid)
        // tetapi status pesanannya masih pending_payment atau draft
        $currentTimestamp = now();

        DB::table('orders')
            ->where('payment_status', 'paid')
            ->whereIn('status', ['draft', 'pending_payment'])
            ->whereNull('paid_at')
            ->update([
                'paid_at' => $currentTimestamp,
            ]);

        DB::table('orders')
            ->where('payment_status', 'paid')
            ->whereIn('status', ['draft', 'pending_payment'])
            ->update([
                'status' => 'processing',
                'updated_at' => $currentTimestamp,
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak perlu revert perubahan status
    }
};
