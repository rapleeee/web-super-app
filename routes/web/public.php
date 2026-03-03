<?php

use App\Http\Controllers\KepegawaianTu\SuratController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::view('/offline', 'offline')->name('offline');
Route::get('/verifikasi-surat-tu/{tuSurat}/{token}', [SuratController::class, 'verify'])->name('kepegawaian-tu.surat.verify');
