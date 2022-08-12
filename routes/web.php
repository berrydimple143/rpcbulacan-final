<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Registration\Registration;
use App\Http\Livewire\Registration\PdfViewer;
use App\Http\Livewire\Administration\Login;
use App\Http\Livewire\Administration\Deactivated;
use App\Http\Livewire\Administration\Register;
use App\Http\Livewire\Administration\Dashboard;
use App\Http\Livewire\Administration\User;
use App\Http\Livewire\Administration\Role;
use App\Http\Livewire\Administration\Permission;
use App\Http\Livewire\Administration\Registrant;
use App\Http\Livewire\Administration\Digital;
use App\Http\Livewire\Administration\Password;
use App\Http\Controllers\HelperController;

Route::get('/', Registration::class)->name('home');
Route::get('/pdf', PdfViewer::class)->name('pdf');
Route::get('/pdfdownload', [HelperController::class, 'pdfdownload'])->name('pdfdownload');
Route::get('/control/login', Login::class)->name('login');
Route::get('/account-deactivated', Deactivated::class)->name('account.deactivated');
Route::get('/create-admin-user', Register::class)->name('register');
Route::get('/update/data/{type}/{code}/{name}', [HelperController::class, 'populateData'])->name('update.data');
Route::get('/update/data-by-name/{type}/{mun}/{bar}', [HelperController::class, 'populateData2'])->name('update.byname');
Route::get('/update/birth/{year}', [HelperController::class, 'birth'])->name('update.birth');

Route::get('/users/export', [HelperController::class, 'export'])->name('export');
Route::get('/users/export/municipality/{mun}', [HelperController::class, 'municipality'])->name('export.municipality');
Route::get('/users/export/barangay/{mun}/{bar}', [HelperController::class, 'barangay'])->name('export.barangay');
Route::middleware('auth')->group(function () {
    Route::prefix('control')->group(function() {
        Route::get('/', Dashboard::class)->name('dashboard');
        Route::get('/users', User::class)->name('users');
        Route::get('/registrants', Registrant::class)->name('registrants');
        Route::get('/digital/id', Digital::class)->name('digital.id');
        Route::get('/user/roles', Role::class)->name('roles');
        Route::get('/user/permissions', Permission::class)->name('permissions');
        Route::get('/change-password', Password::class)->name('change.password');
    });
    Route::get('/logout', [HelperController::class, 'logout'])->name('logout');
});