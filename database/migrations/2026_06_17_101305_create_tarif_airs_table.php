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
        Schema::create('tarif_airs', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('golongan');
            $table->text('deskripsi')->nullable();

            // Tarif Blok Progresif (Hardcoded: Blok 1: 0-10, Blok 2: 11-20, Blok 3: 21-30, Blok 4: >30)
            $table->decimal('tarif_blok_1', 10, 2); // Tarif untuk 0-10 m³
            $table->decimal('tarif_blok_2', 10, 2); // Tarif untuk 11-20 m³
            $table->decimal('tarif_blok_3', 10, 2); // Tarif untuk 21-30 m³
            $table->decimal('tarif_blok_4', 10, 2); // Tarif untuk > 30 m³

            $table->decimal('biaya_pemeliharaan', 10, 2)->default(0);
            $table->integer('minimal_pakai_m3')->default(5);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarif_airs');
    }
};
