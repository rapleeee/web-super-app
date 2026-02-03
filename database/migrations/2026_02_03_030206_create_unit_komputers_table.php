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
        Schema::create('unit_komputers', function (Blueprint $table) {
            $table->id();
            $table->string('kode_unit')->unique();
            $table->string('nama');
            $table->foreignId('laboratorium_id')->constrained('laboratoriums')->cascadeOnDelete();
            $table->integer('nomor_meja')->nullable();
            $table->enum('kondisi', ['baik', 'rusak_ringan', 'rusak_berat', 'mati_total'])->default('baik');
            $table->enum('status', ['aktif', 'dalam_perbaikan', 'tidak_aktif'])->default('aktif');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_komputers');
    }
};
