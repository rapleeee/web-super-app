<?php

use App\Http\Controllers\Laboran\BackupController;
use App\Http\Controllers\Laboran\BeritaAcaraController;
use App\Http\Controllers\Laboran\GuruController;
use App\Http\Controllers\Laboran\KategoriPerangkatController;
use App\Http\Controllers\Laboran\KelasController;
use App\Http\Controllers\Laboran\KomponenPerangkatController;
use App\Http\Controllers\Laboran\LaboranController as PetugasLaboranController;
use App\Http\Controllers\Laboran\LaboratoriumController;
use App\Http\Controllers\Laboran\MaintenanceLogController;
use App\Http\Controllers\Laboran\MataPelajaranController;
use App\Http\Controllers\Laboran\NotificationController;
use App\Http\Controllers\Laboran\UnitKomputerController;
use App\Http\Controllers\LaboranController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:laboran,staff'])->group(function () {
    Route::prefix('laboran')->name('laboran.')->group(function () {
        Route::resource('petugas', PetugasLaboranController::class)->parameters(['petugas' => 'petugas']);
        Route::resource('laboratorium', LaboratoriumController::class);

        Route::resource('kategori-perangkat', KategoriPerangkatController::class);

        Route::get('unit-komputer/import', [UnitKomputerController::class, 'importForm'])->name('unit-komputer.import');
        Route::post('unit-komputer/import/preview', [UnitKomputerController::class, 'importPreview'])->name('unit-komputer.import.preview');
        Route::post('unit-komputer/import/process', [UnitKomputerController::class, 'importProcess'])->name('unit-komputer.import.process');
        Route::get('unit-komputer/template', [UnitKomputerController::class, 'downloadTemplate'])->name('unit-komputer.template');
        Route::delete('unit-komputer/bulk-delete', [UnitKomputerController::class, 'bulkDelete'])->name('unit-komputer.bulk-delete');
        Route::patch('unit-komputer/bulk-update', [UnitKomputerController::class, 'bulkUpdate'])->name('unit-komputer.bulk-update');
        Route::resource('unit-komputer', UnitKomputerController::class);

        Route::resource('komponen-perangkat', KomponenPerangkatController::class);

        Route::get('maintenance-log/komponens/{unitKomputer}', [MaintenanceLogController::class, 'getKomponensByUnit'])->name('maintenance-log.komponens');
        Route::get('maintenance-log/export', [MaintenanceLogController::class, 'export'])->name('maintenance-log.export');
        Route::resource('maintenance-log', MaintenanceLogController::class);

        Route::get('export/weekly-report', [LaboranController::class, 'exportWeeklyReport'])->name('export.weekly-report');

        Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::get('notifications/recent', [NotificationController::class, 'recent'])->name('notifications.recent');
        Route::get('notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
        Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
        Route::post('notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
        Route::delete('notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
        Route::delete('notifications', [NotificationController::class, 'destroyAll'])->name('notifications.destroy-all');

        Route::get('berita-acara/export', [BeritaAcaraController::class, 'export'])->name('berita-acara.export');
        Route::resource('berita-acara', BeritaAcaraController::class);

        Route::resource('data-master/guru', GuruController::class)->names('data-master.guru');
        Route::resource('data-master/mata-pelajaran', MataPelajaranController::class)->names('data-master.mata-pelajaran');
        Route::resource('data-master/kelas', KelasController::class)->names('data-master.kelas');

        Route::get('backup', [BackupController::class, 'index'])->name('backup.index');
        Route::get('backup/download', [BackupController::class, 'download'])->name('backup.download');
        Route::post('backup', [BackupController::class, 'store'])->name('backup.store');
        Route::get('backup/file/{filename}', [BackupController::class, 'downloadFile'])->name('backup.download-file');
        Route::delete('backup/{filename}', [BackupController::class, 'destroy'])->name('backup.destroy');
    });

    // Keep wildcard route last to avoid collision with prefixed laboran routes.
    Route::resource('laboran', LaboranController::class);
});
