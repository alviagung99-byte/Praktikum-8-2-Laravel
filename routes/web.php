<?php

use App\Http\Controllers\LaporBanjirController;
use App\Http\Controllers\PendaftaranSiswaController;
use App\Modules\SmartEdu\Auth\AuthController;
use App\Modules\SmartEdu\Guru\GuruController;
use App\Modules\SmartEdu\OrangTua\OrangTuaController;
use App\Modules\SmartEdu\Siswa\SiswaController;
use App\Modules\SmartEdu\StaffTu\StaffTuController;
use Illuminate\Support\Facades\Route;

Route::get('/laporbanjir', [LaporBanjirController::class, 'form'])->name('laporbanjir.form');
Route::post('/laporbanjir/kirim', [LaporBanjirController::class, 'kirim'])->name('laporbanjir.kirim');
Route::get('/laporbanjir/daftar', [LaporBanjirController::class, 'daftar'])->name('laporbanjir.daftar');

// Halaman utama / landing
Route::get('/', [AuthController::class, 'landing'])->name('home');

// Auth
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard & fitur per role
Route::middleware('smartedu.auth:staff-tu')->group(function () {
    Route::get('/staff-tu/dashboard', [StaffTuController::class, 'staffDashboard'])->name('staff-tu.dashboard');

    // Staff TU — Manajemen Pengguna
    Route::get('/staff-tu/pengguna', [StaffTuController::class, 'pengguna'])->name('staff-tu.pengguna');
    Route::get('/staff-tu/pengguna/tambah', [StaffTuController::class, 'createPengguna'])->name('staff-tu.pengguna.create');
    Route::post('/staff-tu/pengguna/tambah', [StaffTuController::class, 'storePengguna'])->name('staff-tu.pengguna.store');
    Route::get('/staff-tu/pengguna/{id}/edit', [StaffTuController::class, 'editPengguna'])->name('staff-tu.pengguna.edit');
    Route::put('/staff-tu/pengguna/{id}', [StaffTuController::class, 'updatePengguna'])->name('staff-tu.pengguna.update');
    Route::delete('/staff-tu/pengguna/{id}', [StaffTuController::class, 'destroyPengguna'])->name('staff-tu.pengguna.destroy');

    // Staff TU — Manajemen Kelas
    Route::get('/staff-tu/kelas', [StaffTuController::class, 'kelas'])->name('staff-tu.kelas');

    // Staff TU — Manajemen Mata Pelajaran
    Route::get('/staff-tu/mapel', [StaffTuController::class, 'mapel'])->name('staff-tu.mapel');
    Route::get('/staff-tu/mapel/data', [StaffTuController::class, 'mapelData'])->name('staff-tu.mapel.data');
    Route::post('/staff-tu/mapel/upload', [StaffTuController::class, 'uploadMapelFile'])->name('staff-tu.mapel.upload');
    Route::post('/staff-tu/mapel/ajax', [StaffTuController::class, 'ajaxStoreMapel'])->name('staff-tu.mapel.ajax.store');
    Route::put('/staff-tu/mapel/{id}/ajax', [StaffTuController::class, 'ajaxUpdateMapel'])->name('staff-tu.mapel.ajax.update');
    Route::delete('/staff-tu/mapel/{id}/ajax', [StaffTuController::class, 'ajaxDeleteMapel'])->name('staff-tu.mapel.ajax.delete');
});

Route::middleware('smartedu.auth:guru')->group(function () {
    Route::get('/guru/dashboard', [GuruController::class, 'guruDashboard'])->name('guru.dashboard');
});

Route::middleware('smartedu.auth:siswa')->group(function () {
    Route::get('/siswa/dashboard', [SiswaController::class, 'siswaDashboard'])->name('siswa.dashboard');

    // Siswa — Materi
    Route::get('/siswa/materi', [SiswaController::class, 'materi'])->name('siswa.materi');
    Route::get('/siswa/materi/{kodeMateri}', [SiswaController::class, 'detailMateri'])->name('siswa.materi.detail');

    // Siswa — Latihan
    Route::get('/siswa/latihan', [SiswaController::class, 'latihan'])->name('siswa.latihan');
    Route::get('/siswa/latihan/{kodeMateri}', [SiswaController::class, 'detailLatihan'])->name('siswa.latihan.detail');
});

Route::middleware('smartedu.auth:orang-tua')->group(function () {
    Route::get('/orang-tua/dashboard', [OrangTuaController::class, 'orangTuaDashboard'])->name('orang-tua.dashboard');
});

Route::get('/pendaftaran', [PendaftaranSiswaController::class, 'form'])->name('pendaftaran.form');
Route::post('/pendaftaran/proses', [PendaftaranSiswaController::class, 'proses'])->name('pendaftaran.proses');
