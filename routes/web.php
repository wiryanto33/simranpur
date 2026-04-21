<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

Route::get('/dashboard', function () {
    return view('dashboard.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::resource('kendaraan', \App\Http\Controllers\KendaraanController::class);
    Route::resource('kompi', \App\Http\Controllers\KompiController::class);
    Route::resource('jadwal', \App\Http\Controllers\JadwalController::class);
    Route::get('/jadwal/{id}/cetak-pdf', [\App\Http\Controllers\JadwalController::class, 'cetakPdf'])->name('jadwal.cetak-pdf');
    Route::get('/suku-cadang', [\App\Http\Controllers\SukuCadangController::class, 'index'])->name('suku-cadang.index');
    Route::resource('laporan-kerusakan', \App\Http\Controllers\LaporanKerusakanController::class);
    Route::resource('laporan-perbaikan', \App\Http\Controllers\LaporanPerbaikanController::class);
    Route::get('/laporan-perbaikan/{id}/cetak-pdf', [\App\Http\Controllers\LaporanPerbaikanController::class, 'cetakPdf'])->name('laporan-perbaikan.cetak-pdf');
    Route::get('/users', [\App\Http\Controllers\UserController::class, 'index'])->name('user.index');
    Route::get('/notifications', \App\Livewire\NotificationIndex::class)->name('notifications.index');
    Route::get('/permintaan-suku-cadang', [\App\Http\Controllers\PermintaanSukuCadangController::class, 'index'])->name('permintaan-suku-cadang.index');
    Route::get('/roles', [\App\Http\Controllers\RoleController::class, 'index'])->name('role.index');

    // Laporan & Rekap
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/kesiapan', [\App\Http\Controllers\LaporanController::class, 'kesiapan'])
            ->name('kesiapan')
            ->middleware('permission:view_laporan_rekap_kesiapan');

        Route::get('/pemeliharaan', [\App\Http\Controllers\LaporanController::class, 'pemeliharaan'])
            ->name('pemeliharaan')
            ->middleware('permission:view_laporan_rekap_pemeliharaan');

        Route::get('/kerusakan', [\App\Http\Controllers\LaporanController::class, 'kerusakan'])
            ->name('kerusakan')
            ->middleware('permission:view_laporan_rekap_kerusakan');

        Route::get('/suku-cadang', [\App\Http\Controllers\LaporanController::class, 'sukuCadang'])
            ->name('suku-cadang')
            ->middleware('permission:view_laporan_rekap_suku_cadang');

        Route::get('/audit-log', [\App\Http\Controllers\LaporanController::class, 'auditLog'])
            ->name('audit-log')
            ->middleware('permission:view_laporan_audit_log');

        // Ekspor PDF/Excel
        Route::get('/export/{type}/{format}', [\App\Http\Controllers\LaporanController::class, 'export'])
            ->name('export')
            ->middleware('permission:export_laporan');
    });

    Route::get('/profile', \App\Livewire\ProfileIndex::class)->name('profile.index');
});

require __DIR__.'/auth.php';
