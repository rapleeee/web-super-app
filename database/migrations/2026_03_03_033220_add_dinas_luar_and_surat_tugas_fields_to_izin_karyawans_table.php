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
        Schema::table('izin_karyawans', function (Blueprint $table) {
            $table->string('dinas_luar_hari', 120)->nullable()->after('alasan');
            $table->string('dinas_luar_waktu', 120)->nullable()->after('dinas_luar_hari');
            $table->string('dinas_luar_tempat', 200)->nullable()->after('dinas_luar_waktu');
            $table->string('surat_tugas_nomor', 120)->nullable()->after('catatan_persetujuan');
            $table->text('surat_tugas_sebagai')->nullable()->after('surat_tugas_nomor');
            $table->timestamp('surat_tugas_diterbitkan_at')->nullable()->after('surat_tugas_sebagai');
            $table->timestamp('surat_tugas_signed_at')->nullable()->after('surat_tugas_diterbitkan_at');
            $table->string('surat_tugas_signature_token', 64)->nullable()->after('surat_tugas_signed_at');

            $table->index('surat_tugas_nomor', 'izin_surat_tugas_nomor_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('izin_karyawans', function (Blueprint $table) {
            $table->dropIndex('izin_surat_tugas_nomor_idx');
            $table->dropColumn([
                'dinas_luar_hari',
                'dinas_luar_waktu',
                'dinas_luar_tempat',
                'surat_tugas_nomor',
                'surat_tugas_sebagai',
                'surat_tugas_diterbitkan_at',
                'surat_tugas_signed_at',
                'surat_tugas_signature_token',
            ]);
        });
    }
};
