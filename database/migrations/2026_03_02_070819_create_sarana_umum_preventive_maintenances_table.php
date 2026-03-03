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
        Schema::create('sarana_umum_preventive_maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sarana_umum_id')->constrained('sarana_umums')->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nama_tugas');
            $table->text('deskripsi')->nullable();
            $table->unsignedSmallInteger('interval_hari')->default(30);
            $table->unsignedTinyInteger('toleransi_hari')->default(0);
            $table->date('tanggal_mulai');
            $table->date('tanggal_maintenance_terakhir')->nullable();
            $table->date('tanggal_maintenance_berikutnya');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'tanggal_maintenance_berikutnya'], 'preventive_next_due_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sarana_umum_preventive_maintenances');
    }
};
