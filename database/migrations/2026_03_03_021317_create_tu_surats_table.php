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
        Schema::create('tu_surats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tu_surat_template_id')->nullable()->constrained('tu_surat_templates')->nullOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nomor_surat')->nullable()->unique();
            $table->string('perihal');
            $table->string('tujuan');
            $table->date('tanggal_surat')->nullable();
            $table->longText('isi_surat');
            $table->enum('status', ['draft', 'review', 'final', 'arsip'])->default('draft');
            $table->string('verification_token', 64)->nullable()->unique();
            $table->timestamp('finalized_at')->nullable();
            $table->timestamp('archived_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'tanggal_surat'], 'tu_surat_status_tanggal_idx');
            $table->index(['created_by', 'status'], 'tu_surat_creator_status_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tu_surats');
    }
};
