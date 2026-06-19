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
        Schema::create('billings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelanggan_id')->constrained('pelanggans')->cascadeOnDelete();
            $table->string('periode', 7); // YYYY-MM
            $table->integer('meter_awal');
            $table->integer('meter_akhir');
            $table->integer('pemakaian');
            $table->decimal('tagihan_air', 15, 2);
            $table->decimal('abonemen', 10, 2);
            $table->decimal('total_tagihan', 15, 2);
            $table->string('status_pembayaran')->default('belum_lunas');
            $table->timestamp('tanggal_bayar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billings');
    }
};
