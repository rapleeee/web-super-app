<?php

use App\Http\Controllers\SaranaUmum\AuditLogController as SaranaUmumAuditLogController;
use App\Http\Controllers\SaranaUmum\BackupController as SaranaUmumBackupController;
use App\Http\Controllers\SaranaUmum\BeritaAcaraController as SaranaUmumBeritaAcaraController;
use App\Http\Controllers\SaranaUmum\DashboardController as SaranaUmumDashboardController;
use App\Http\Controllers\SaranaUmum\DataSaranaController;
use App\Http\Controllers\SaranaUmum\GuruController as SaranaUmumGuruController;
use App\Http\Controllers\SaranaUmum\KategoriSaranaController;
use App\Http\Controllers\SaranaUmum\MaintenanceLogController as SaranaUmumMaintenanceLogController;
use App\Http\Controllers\SaranaUmum\PetugasSarprasController;
use App\Http\Controllers\SaranaUmum\PreventiveMaintenanceController;
use App\Http\Controllers\SaranaUmum\RuanganController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:laboran,staff'])->group(function () {
    Route::prefix('sarana-umum')->name('sarana-umum.')->middleware('sarana-umum.access')->group(function () {
        Route::get('/', [SaranaUmumDashboardController::class, 'index'])->name('dashboard');

        Route::get('data-sarana/import', [DataSaranaController::class, 'importForm'])->name('data-sarana.import');
        Route::post('data-sarana/import/preview', [DataSaranaController::class, 'importPreview'])->name('data-sarana.import.preview');
        Route::post('data-sarana/import/process', [DataSaranaController::class, 'importProcess'])->name('data-sarana.import.process');
        Route::get('data-sarana/template', [DataSaranaController::class, 'downloadTemplate'])->name('data-sarana.template');
        Route::get('data-sarana/{sarana_umum}/qr', [DataSaranaController::class, 'qr'])->name('data-sarana.qr');

        Route::resource('data-sarana', DataSaranaController::class)->parameters(['data-sarana' => 'sarana_umum']);
        Route::resource('petugas-sarpras', PetugasSarprasController::class)->parameters(['petugas-sarpras' => 'petugas']);
        Route::resource('data-guru', SaranaUmumGuruController::class)->parameters(['data-guru' => 'guru']);
        Route::resource('data-ruangan', RuanganController::class)->parameters(['data-ruangan' => 'laboratorium']);
        Route::resource('kategori-sarana', KategoriSaranaController::class)->parameters(['kategori-sarana' => 'kategori_perangkat']);
        Route::resource('maintenance-log', SaranaUmumMaintenanceLogController::class)->parameters(['maintenance-log' => 'maintenanceLog']);
        Route::resource('preventive-maintenance', PreventiveMaintenanceController::class)->parameters(['preventive-maintenance' => 'preventiveMaintenance']);
        Route::patch('preventive-maintenance/{preventiveMaintenance}/complete', [PreventiveMaintenanceController::class, 'complete'])->name('preventive-maintenance.complete');

        Route::get('berita-acara/export', [SaranaUmumBeritaAcaraController::class, 'export'])->name('berita-acara.export');
        Route::resource('berita-acara', SaranaUmumBeritaAcaraController::class)->parameters(['berita-acara' => 'beritaAcara']);

        Route::get('audit-log', [SaranaUmumAuditLogController::class, 'index'])->name('audit-log.index');

        Route::get('backup', [SaranaUmumBackupController::class, 'index'])->name('backup.index');
        Route::get('backup/download', [SaranaUmumBackupController::class, 'download'])->name('backup.download');
        Route::post('backup', [SaranaUmumBackupController::class, 'store'])->name('backup.store');
        Route::get('backup/file/{filename}', [SaranaUmumBackupController::class, 'downloadFile'])->name('backup.download-file');
        Route::delete('backup/{filename}', [SaranaUmumBackupController::class, 'destroy'])->name('backup.destroy');
    });
});
