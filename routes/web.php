<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EditController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UnitKerjaController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\SubRegistrationController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UnitKerjaController as AdminUnitKerjaController; // Akan dibuat
use App\Http\Controllers\Admin\UserController as AdminUserController; // Akan dibuat



Route::get('/layout-lw', [HomeController::class, 'layoutLw']);

Route::get('/verify/{token}', [App\Http\Controllers\VerificationController::class, 'verify'])->name('verification.page');


//  jika user belum login
Route::group(['middleware' => 'guest'], function() {
    Route::get('/', [LoginController::class, 'ShowLoginForm'])->name('login');
    Route::post('/', [LoginController::class, 'dologin'])->name('login');

});

Route::get('/get-unit-kerja-options', [UnitKerjaController::class, 'getUnitKerjaOptions']);
Route::get('/get-roles', [UnitKerjaController::class, 'getRoles']);

Route::group(['middleware' => ['auth', 'AuthenticateDashboard:1,2,3,4']], function() { // Tambahkan 4 di sini
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/redirect', [DashboardController::class, 'cek'])->name('redirect');
});

// Rute untuk Manajemen Admin (role_id 1)
Route::group(['middleware' => ['auth', 'AuthenticateDashboard:1'], 'prefix' => 'admin', 'as' => 'admin.'], function() {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/daftar-unit-kerja', [AdminUnitKerjaController::class, 'index'])->name('unit-kerja.index');
    Route::get('/buat-user', [AdminUnitKerjaController::class, 'create'])->name('unit-kerja.create');
    Route::post('/unit-kerja', [AdminUnitKerjaController::class, 'store'])->name('unit-kerja.store');
    Route::get('/unit-kerja/{unit_kerja}/edit', [AdminUnitKerjaController::class, 'edit'])->name('unit-kerja.edit');
    Route::put('/unit-kerja/{unit_kerja}', [AdminUnitKerjaController::class, 'update'])->name('unit-kerja.update');
    Route::delete('/unit-kerja/{unit_kerja}', [AdminUnitKerjaController::class, 'destroy'])->name('unit-kerja.destroy');

});


// Rute untuk role VP Kamtib (Kantor) - id 2 (sebelumnya saya kira 1)
Route::group(['middleware' => ['auth', 'AuthenticateDashboard:2']], function() { // Ubah dari 1 menjadi 2 jika itu ID untuk VP Kamtib
    Route::get('/DashboardKantor', [DashboardController::class, 'user1'])->name('Approval.DashboardKantor');
    Route::get('/DashboardKantor/check/{submissionId}', [DashboardController::class, 'CheckSubmission'])->name('Approval.CheckSubmissionKantor'); // Nama rute unik
    Route::post('/DashboardKantor/check/approve/{submissionId}', [ApprovalController::class, 'approveSubmissionKantor'])->name('approval.Kantor.approve');
    Route::post('/DashboardKantor/check/reject/{submissionId}', [ApprovalController::class, 'rejectSubmissionKantor'])->name('approval.Kantor.reject');
});

// Rute untuk role VP Operasi 1 (Pabrik) - id 3 (sebelumnya saya kira 2)
Route::group(['middleware' => ['auth', 'AuthenticateDashboard:3']], function() { // Ubah dari 2 menjadi 3 jika itu ID untuk VP Operasi 1
    Route::get('/DashboardPabrik', [DashboardController::class, 'user2'])->name('Approval.DashboardPabrik');
    Route::get('/DashboardPabrik/check/{submissionId}', [DashboardController::class, 'CheckSubmission'])->name('Approval.CheckSubmissionPabrik'); // Nama rute unik
    Route::post('/DashboardPabrik/check/approve/{submissionId}', [ApprovalController::class, 'approveSubmissionPabrik'])->name('approval.Pabrik.approve');
    Route::post('/DashboardPabrik/check/reject/{submissionId}', [ApprovalController::class, 'rejectSubmissionPabrik'])->name('approval.Pabrik.reject');
});

// Rute untuk Pemohon (Unit Kerja) - id 4 (sebelumnya saya kira 3)
Route::group(['middleware' => ['auth', 'AuthenticateDashboard:4']], function() { // Ubah dari 3 menjadi 4
    Route::get('/DashboardPemohon', [DashboardController::class, 'showDashboardPemohon'])->name('Pemohon.DashboardPemohon');
    Route::get('/DashboardPemohon/registration', [RegistrationController::class, 'showRegistrationForm'])->name('registration.form');
    Route::post('/DashboardPemohon/registration/submit', [RegistrationController::class, 'submitForm'])->name('registration.submit');
    Route::get('/DashboardPemohon/{submissionId}/sub-registration/', [SubRegistrationController::class, 'lookForm'])->name('sub-registration.form');
    Route::get('/DashboardPemohon/{submissionId}/daftar_tamu/', [SubRegistrationController::class, 'DaftarTamu'])->name('daftar-tamu');
    Route::post('/DashboardPemohon/{submissionId}/sub-registration/submit', [SubRegistrationController::class, 'submitSubForm'])->name('sub-registration.submit');
    Route::get('DashboardPemohon/{submissionId}/daftar_tamu/{dataSubmissionId}/edit', [EditController::class, 'edit'])->name('daftar_tamu.edit');
    Route::put('DashboardPemohon/{submissionId}/daftar_tamu/{dataSubmissionId}/update', [EditController::class, 'update'])->name('daftar_tamu.update');
    Route::delete('DashboardPemohon/{submissionId}/daftar_tamu/{dataSubmissionId}/delete', [EditController::class, 'deleteDataSubmission'])->name('daftar_tamu.delete'); // Menggunakan deleteDataSubmission
    Route::delete('DashboardPemohon/{submissionId}/delete-form', [EditController::class, 'deleteForm'])->name('deleteForm');
    Route::put('/update/{submissionId}', [EditController::class, 'updateForm'])->name('updateForm');
    Route::get('/DashboardPemohon/{submissionId}/konfirmasi-persetujuan', [DashboardController::class, 'showApprovalConfirmation'])->name('Pemohon.konfirmasi-persetujuan');
});
