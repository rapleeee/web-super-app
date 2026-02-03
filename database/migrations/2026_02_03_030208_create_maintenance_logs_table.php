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
        Schema::create('maintenance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('komponen_perangkat_id')->constrained('komponen_perangkats')->cascadeOnDelete();
            $table->foreignId('pelapor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('tanggal_lapor');
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->text('keluhan');
            $table->text('diagnosa')->nullable();
            $table->text('tindakan')->nullable();
            $table->string('teknisi')->nullable();
            $table->decimal('biaya', 12, 2)->nullable();
            $table->enum('status', ['pending', 'proses', 'selesai', 'tidak_bisa_diperbaiki'])->default('pending');
            $table->enum('kondisi_sebelum', ['baik', 'rusak_ringan', 'rusak_berat', 'mati_total']);
            $table->enum('kondisi_sesudah', ['baik', 'rusak_ringan', 'rusak_berat', 'mati_total'])->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_logs');
    }
};
