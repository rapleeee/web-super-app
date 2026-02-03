<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaboranController;
use App\Http\Controllers\Laboran\KategoriPerangkatController;
use App\Http\Controllers\Laboran\KomponenPerangkatController;
use App\Http\Controllers\Laboran\LaboranController as PetugasLaboranController;
use App\Http\Controllers\Laboran\LaboratoriumController;
use App\Http\Controllers\Laboran\MaintenanceLogController;
use App\Http\Controllers\Laboran\NotificationController;
use App\Http\Controllers\Laboran\UnitKomputerController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware('auth')->group( function() {
    Route::resource('dashboard', DashboardController::class);
});

Route::middleware(['auth', 'role:admin,laboran,staff'])->group(function() {
    // Specific routes first (more specific before wildcard)
    Route::prefix('laboran')->name('laboran.')->group(function () {
        // Master Data
        Route::resource('petugas', PetugasLaboranController::class)->parameters(['petugas' => 'petugas']);
        Route::resource('laboratorium', LaboratoriumController::class);

        // Perangkat Management
        Route::resource('kategori-perangkat', KategoriPerangkatController::class);

        // Unit Komputer with import
        Route::get('unit-komputer/import', [UnitKomputerController::class, 'importForm'])->name('unit-komputer.import');
        Route::post('unit-komputer/import/preview', [UnitKomputerController::class, 'importPreview'])->name('unit-komputer.import.preview');
        Route::post('unit-komputer/import/process', [UnitKomputerController::class, 'importProcess'])->name('unit-komputer.import.process');
        Route::get('unit-komputer/template', [UnitKomputerController::class, 'downloadTemplate'])->name('unit-komputer.template');
        Route::resource('unit-komputer', UnitKomputerController::class);

        Route::resource('komponen-perangkat', KomponenPerangkatController::class);

        // Maintenance Log with API
        Route::get('maintenance-log/komponens/{unitKomputer}', [MaintenanceLogController::class, 'getKomponensByUnit'])->name('maintenance-log.komponens');
        Route::resource('maintenance-log', MaintenanceLogController::class);

        // Weekly Report Export
        Route::get('export/weekly-report', [LaboranController::class, 'exportWeeklyReport'])->name('export.weekly-report');

        // Notifications
        Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::get('notifications/recent', [NotificationController::class, 'recent'])->name('notifications.recent');
        Route::get('notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
        Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
        Route::post('notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
        Route::delete('notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
        Route::delete('notifications', [NotificationController::class, 'destroyAll'])->name('notifications.destroy-all');
    });

    // Wildcard route last
    Route::resource('laboran', LaboranController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
