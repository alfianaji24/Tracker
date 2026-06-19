<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('billings', function (Blueprint $table) {
            $table->string('no_invoice')->unique()->nullable()->after('id');
        });

        // Generate no_invoice untuk data yang sudah ada
        $billings = \App\Models\Billing::all();
        foreach ($billings as $billing) {
            if (empty($billing->no_invoice)) {
                $tahun = $billing->created_at->format('Y');
                $bulan = $billing->created_at->format('m');
                $nomor = str_pad($billing->id, 3, '0', STR_PAD_LEFT);
                $billing->update(['no_invoice' => 'INV-' . $tahun . '/' . $bulan . '/' . $nomor]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('billings', function (Blueprint $table) {
            $table->dropColumn('no_invoice');
        });
    }
};
