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
        Schema::create('tu_berita_acara_tindak_lanjuts', function (Blueprint $table) {
            $table->id();
            $table->enum('source_type', ['laboran', 'sarana_umum']);
            $table->unsignedBigInteger('source_id');
            $table->enum('status', ['baru', 'diproses', 'selesai', 'arsip'])->default('baru');
            $table->text('catatan')->nullable();
            $table->json('tags')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('archived_at')->nullable();
            $table->timestamps();

            $table->unique(['source_type', 'source_id'], 'tu_ba_tindak_lanjut_source_unique');
            $table->index('status');
            $table->index('processed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tu_berita_acara_tindak_lanjuts');
    }
};
