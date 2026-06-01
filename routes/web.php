<?php

use App\Http\Controllers\Admin\CabinetController;
use App\Http\Controllers\Admin\CommandeController;
use App\Http\Controllers\Admin\ConsultationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ParametreController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\ProduitController;
use App\Http\Controllers\Admin\RendezVousController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect(auth()->user()->dashboardUrl());
    }

    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('patient')->name('patient.')->middleware(['auth', 'verified', 'role:patient'])->group(function () {
    Route::get('/dashboard', fn () => view('patient.dashboard'))->name('dashboard');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'role:super_admin|cabinet_admin|opticien'])->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::resource('cabinets', CabinetController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('users', UserController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('patients', PatientController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('rendezvous', RendezVousController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('produits', ProduitController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('consultations', ConsultationController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('commandes', CommandeController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('parametres', ParametreController::class)->only(['index', 'store', 'update', 'destroy']);
});

require __DIR__.'/auth.php';
