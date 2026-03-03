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
        Schema::create('tu_arsip_dokumens', function (Blueprint $table) {
            $table->id();
            $table->string('module', 80);
            $table->string('source_type', 120)->nullable();
            $table->unsignedBigInteger('source_id')->nullable();
            $table->string('judul');
            $table->string('nomor_dokumen')->nullable();
            $table->date('tanggal_dokumen')->nullable();
            $table->string('status_sumber', 50)->nullable();
            $table->json('tags')->nullable();
            $table->json('metadata')->nullable();
            $table->unsignedInteger('version')->default(1);
            $table->date('retensi_sampai')->nullable();
            $table->timestamp('archived_at')->nullable();
            $table->foreignId('archived_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['module', 'source_type', 'source_id'], 'tu_arsip_source_unique');
            $table->index(['module', 'tanggal_dokumen'], 'tu_arsip_module_tanggal_idx');
            $table->index('nomor_dokumen');
            $table->index('retensi_sampai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tu_arsip_dokumens');
    }
};
