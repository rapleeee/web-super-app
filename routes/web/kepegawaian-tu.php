<?php

use App\Http\Controllers\KepegawaianTu\ApprovalCenterController;
use App\Http\Controllers\KepegawaianTu\ArsipDokumenController;
use App\Http\Controllers\KepegawaianTu\AuditLogController;
use App\Http\Controllers\KepegawaianTu\BeritaAcaraFinalController as TuBeritaAcaraFinalController;
use App\Http\Controllers\KepegawaianTu\DashboardController as TuDashboardController;
use App\Http\Controllers\KepegawaianTu\IzinKaryawanController;
use App\Http\Controllers\KepegawaianTu\SuratController;
use App\Http\Controllers\KepegawaianTu\SuratTemplateController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::prefix('kepegawaian-tu')->name('kepegawaian-tu.')->group(function () {
        Route::get('izin-karyawan/export', [IzinKaryawanController::class, 'export'])->name('izin-karyawan.export');
        Route::get('izin-karyawan/{izinKaryawan}/surat-tugas', [IzinKaryawanController::class, 'suratTugas'])
            ->name('izin-karyawan.surat-tugas');
        Route::patch('izin-karyawan/{izinKaryawan}/approval', [IzinKaryawanController::class, 'approval'])
            ->name('izin-karyawan.approval')
            ->middleware('role:admin,pejabat');
        Route::resource('izin-karyawan', IzinKaryawanController::class)->parameters(['izin-karyawan' => 'izinKaryawan']);
    });
});

Route::middleware(['auth', 'role:laboran,staff'])->group(function () {
    Route::prefix('kepegawaian-tu')->name('kepegawaian-tu.')->group(function () {
        Route::get('/', [TuDashboardController::class, 'index'])->name('dashboard');
        Route::get('pusat-approval', [ApprovalCenterController::class, 'index'])->name('pusat-approval.index')->middleware('role:admin,pejabat');
        Route::get('berita-acara-final', [TuBeritaAcaraFinalController::class, 'index'])->name('berita-acara-final.index');
        Route::get('berita-acara-final/export', [TuBeritaAcaraFinalController::class, 'export'])->name('berita-acara-final.export');
        Route::patch('berita-acara-final/{sourceType}/{sourceId}/tindak-lanjut', [TuBeritaAcaraFinalController::class, 'updateTindakLanjut'])
            ->name('berita-acara-final.tindak-lanjut')
            ->middleware('role:admin,pejabat');
        Route::get('arsip-digital', [ArsipDokumenController::class, 'index'])->name('arsip-digital.index');
        Route::get('arsip-digital/{tuArsipDokumen}', [ArsipDokumenController::class, 'show'])->name('arsip-digital.show');
        Route::patch('arsip-digital/{tuArsipDokumen}', [ArsipDokumenController::class, 'update'])
            ->name('arsip-digital.update')
            ->middleware('role:admin,pejabat');
        Route::get('audit-log', [AuditLogController::class, 'index'])->name('audit-log.index')->middleware('role:admin,pejabat');
        Route::resource('template-surat', SuratTemplateController::class)
            ->except(['show'])
            ->parameters(['template-surat' => 'tuSuratTemplate'])
            ->middleware('role:admin,pejabat');
        Route::patch('surat/{tuSurat}/submit-review', [SuratController::class, 'submitReview'])->name('surat.submit-review');
        Route::patch('surat/{tuSurat}/approve-final', [SuratController::class, 'approveFinal'])->name('surat.approve-final')->middleware('role:admin,pejabat');
        Route::patch('surat/{tuSurat}/archive', [SuratController::class, 'archive'])->name('surat.archive')->middleware('role:admin,pejabat');
        Route::get('surat/{tuSurat}/print', [SuratController::class, 'print'])->name('surat.print');
        Route::resource('surat', SuratController::class)->parameters(['surat' => 'tuSurat']);
    });
});
