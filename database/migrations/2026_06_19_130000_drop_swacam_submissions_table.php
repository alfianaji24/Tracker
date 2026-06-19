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
        Schema::dropIfExists('swacam_submissions');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('swacam_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelanggan_id')->constrained('pelanggans')->cascadeOnDelete();
            $table->string('periode', 7)->nullable();
            $table->integer('meter_awal')->default(0);
            $table->integer('meter_reading');
            $table->integer('pemakaian')->nullable();
            $table->string('photo_path')->nullable();
            $table->integer('photo_quality')->default(0);
            $table->boolean('blur_detected')->default(false);
            $table->integer('brightness_score')->default(50);
            $table->integer('ocr_confidence')->default(0);
            $table->enum('status', ['submitted', 'approved', 'rejected'])->default('submitted');
            $table->text('admin_notes')->nullable();
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }
};
