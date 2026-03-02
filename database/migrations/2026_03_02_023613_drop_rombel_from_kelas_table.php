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
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropUnique('kelas_tingkat_jurusan_rombel_unique');
            $table->dropColumn('rombel');
            $table->unique(['tingkat', 'jurusan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropUnique('kelas_tingkat_jurusan_unique');
            $table->string('rombel')->default('1');
            $table->unique(['tingkat', 'jurusan', 'rombel']);
        });
    }
};
