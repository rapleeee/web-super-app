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
        Schema::create('sarana_umums', function (Blueprint $table) {
            $table->id();
            $table->string('kode_inventaris')->unique();
            $table->string('nama');
            $table->string('jenis');
            $table->string('lokasi');
            $table->string('merk')->nullable();
            $table->string('model')->nullable();
            $table->string('nomor_seri')->nullable();
            $table->unsignedSmallInteger('tahun_pengadaan')->nullable();
            $table->enum('kondisi', ['baik', 'rusak_ringan', 'rusak_berat', 'mati_total'])->default('baik');
            $table->enum('status', ['aktif', 'dalam_perbaikan', 'tidak_aktif'])->default('aktif');
            $table->text('keterangan')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sarana_umums');
    }
};
