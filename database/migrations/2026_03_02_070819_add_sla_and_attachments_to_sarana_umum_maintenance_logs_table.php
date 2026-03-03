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
        Schema::table('sarana_umum_maintenance_logs', function (Blueprint $table) {
            $table->date('sla_deadline')->nullable()->after('tanggal_selesai');
            $table->timestamp('reminder_sent_at')->nullable()->after('sla_deadline');
            $table->string('bukti_sebelum')->nullable()->after('catatan');
            $table->string('bukti_sesudah')->nullable()->after('bukti_sebelum');
            $table->string('bukti_invoice')->nullable()->after('bukti_sesudah');

            $table->index(['status', 'sla_deadline'], 'maintenance_sla_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sarana_umum_maintenance_logs', function (Blueprint $table) {
            $table->dropIndex('maintenance_sla_idx');
            $table->dropColumn([
                'sla_deadline',
                'reminder_sent_at',
                'bukti_sebelum',
                'bukti_sesudah',
                'bukti_invoice',
            ]);
        });
    }
};
