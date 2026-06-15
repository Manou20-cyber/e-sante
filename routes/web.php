<?php

use App\Http\Controllers\Admin\CabinetController;
use App\Http\Controllers\Admin\CommandeController;
use App\Http\Controllers\Admin\ConsultationController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DossierCabinetController;
use App\Http\Controllers\Admin\ExamenController;
use App\Http\Controllers\Admin\MessageAdminController;
use App\Http\Controllers\Admin\OpticienController;
use App\Http\Controllers\Admin\ParametreController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\PlanningController;
use App\Http\Controllers\Admin\ProduitController;
use App\Http\Controllers\Admin\RendezVousController;
use App\Http\Controllers\Admin\RetourController;
use App\Http\Controllers\Admin\StatistiquesController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\RegisterCabinetController;
use App\Http\Controllers\Patient\CabinetController as PatientCabinetController;
use App\Http\Controllers\Patient\CommandeController as PatientCommandeController;
use App\Http\Controllers\Patient\DashboardController as PatientDashboardController;
use App\Http\Controllers\Patient\DocumentController as PatientDocumentController;
use App\Http\Controllers\Patient\DossierController as PatientDossierController;
use App\Http\Controllers\Patient\FactureController as PatientFactureController;
use App\Http\Controllers\Patient\HistoriqueController as PatientHistoriqueController;
use App\Http\Controllers\Patient\MessageController as PatientMessageController;
use App\Http\Controllers\Patient\NotificationController as PatientNotificationController;
use App\Http\Controllers\Patient\PaiementController as PatientPaiementController;
use App\Http\Controllers\Patient\RendezVousController as PatientRendezVousController;
use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect(auth()->user()->dashboardUrl());
    }

    return view('welcome');
})->name('welcome');

// Route catch-all pour les controllers Breeze (reset password, verify email, etc.)
Route::get('/dashboard', function () {
    /** @var User $user */
    $user = auth()->user();

    return redirect($user->dashboardUrl());
})->middleware(['auth'])->name('dashboard');

Route::middleware('guest')->group(function () {
    Route::get('/register/cabinet', [RegisterCabinetController::class, 'create'])->name('register.cabinet');
    Route::post('/register/cabinet', [RegisterCabinetController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('patient')->name('patient.')->middleware(['auth', 'verified', 'role:patient'])->group(function () {
    Route::get('/cabinets', [PatientCabinetController::class, 'index'])->name('cabinets.index');
    Route::get('/cabinets/{cabinet}', [PatientCabinetController::class, 'show'])->name('cabinets.show');
    Route::get('/cabinets/{cabinet}/opticiens/{opticien}', [PatientCabinetController::class, 'opticien'])->name('cabinets.opticien');
    Route::post('/cabinets/{cabinet}/opticiens/{opticien}/reserver', [PatientCabinetController::class, 'book'])->name('cabinets.book');
    Route::get('/dashboard', PatientDashboardController::class)->name('dashboard');
    Route::get('/rendezvous', [PatientRendezVousController::class, 'index'])->name('rendezvous.index');
    Route::post('/rendezvous', [PatientRendezVousController::class, 'store'])->name('rendezvous.store');
    Route::put('/rendezvous/{rendezvou}', [PatientRendezVousController::class, 'update'])->name('rendezvous.update');
    Route::delete('/rendezvous/{rendezvou}', [PatientRendezVousController::class, 'destroy'])->name('rendezvous.destroy');
    Route::get('/dossier', [PatientDossierController::class, 'index'])->name('dossier');
    Route::put('/dossier', [PatientDossierController::class, 'update'])->name('dossier.update');
    Route::get('/documents', [PatientDocumentController::class, 'index'])->name('documents.index');
    Route::post('/documents', [PatientDocumentController::class, 'store'])->name('documents.store');
    Route::get('/documents/{document}/download', [PatientDocumentController::class, 'download'])->name('documents.download');
    Route::get('/commandes', [PatientCommandeController::class, 'index'])->name('commandes.index');
    Route::get('/commandes/create', [PatientCommandeController::class, 'create'])->name('commandes.create');
    Route::post('/commandes', [PatientCommandeController::class, 'store'])->name('commandes.store');
    Route::post('/commandes/{commande}/retour', [PatientCommandeController::class, 'retour'])->name('commandes.retour');
    Route::get('/notifications', [PatientNotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/lu', [PatientNotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/tout-lire', [PatientNotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::get('/messages', [PatientMessageController::class, 'index'])->name('messages.index');
    Route::post('/messages', [PatientMessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/{cabinet:uuid}', [PatientMessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{cabinet:uuid}/repondre', [PatientMessageController::class, 'repondre'])->name('messages.repondre');
    Route::get('/historique', PatientHistoriqueController::class)->name('historique');
    Route::get('/factures', [PatientFactureController::class, 'index'])->name('factures.index');
    Route::get('/factures/{facture}', [PatientFactureController::class, 'show'])->name('factures.show');
    Route::post('/factures/{facture}/payer', [PatientPaiementController::class, 'store'])->name('factures.payer');
});

Route::prefix('dashboard')->name('admin.')->middleware(['auth', 'verified', 'role:super_admin|cabinet_admin|opticien'])->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::resource('cabinets', CabinetController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::patch('cabinets/{cabinet}/valider', [CabinetController::class, 'valider'])->name('cabinets.valider');
    Route::resource('users', UserController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('patients', PatientController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('rendezvous', RendezVousController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('produits', ProduitController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('consultations', ConsultationController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('commandes', CommandeController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::post('parametres/apparence', [ParametreController::class, 'saveApparence'])->name('parametres.apparence');
    Route::post('parametres/cabinet', [ParametreController::class, 'saveCabinet'])->name('parametres.cabinet');
    Route::resource('parametres', ParametreController::class)->only(['index', 'store', 'update', 'destroy']);

    // Opticiens (cabinet_admin uniquement)
    Route::middleware('role:cabinet_admin')->group(function () {
        Route::resource('opticiens', OpticienController::class)->only(['index', 'store', 'update', 'destroy']);
    });

    // Planning (opticien + cabinet_admin)
    Route::get('planning', [PlanningController::class, 'index'])->name('planning.index');
    Route::post('planning', [PlanningController::class, 'store'])->name('planning.store');
    Route::put('planning/{creneau}', [PlanningController::class, 'update'])->name('planning.update');
    Route::delete('planning/{creneau}', [PlanningController::class, 'destroy'])->name('planning.destroy');

    // Messagerie admin
    Route::get('messages', [MessageAdminController::class, 'index'])->name('messages.index');
    Route::get('messages/{interlocuteur}', [MessageAdminController::class, 'show'])->name('messages.show');
    Route::post('messages/{interlocuteur}/repondre', [MessageAdminController::class, 'repondre'])->name('messages.repondre');

    // Dossiers médicaux (vue cabinet)
    Route::get('dossiers', [DossierCabinetController::class, 'index'])->name('dossiers.index');
    Route::get('dossiers/{patient}', [DossierCabinetController::class, 'show'])->name('dossiers.show');
    Route::post('dossiers/{patient}/ordonnances', [DossierCabinetController::class, 'storeOrdonnance'])->name('dossiers.ordonnances.store');

    // Examens optiques
    Route::get('examens', [ExamenController::class, 'index'])->name('examens.index');
    Route::post('examens', [ExamenController::class, 'store'])->name('examens.store');
    Route::put('examens/{examen}', [ExamenController::class, 'update'])->name('examens.update');
    Route::delete('examens/{examen}', [ExamenController::class, 'destroy'])->name('examens.destroy');

    // Retours
    Route::get('retours', [RetourController::class, 'index'])->name('retours.index');
    Route::put('retours/{retour}', [RetourController::class, 'update'])->name('retours.update');

    // Statistiques
    Route::get('statistiques', [StatistiquesController::class, 'index'])->name('statistiques.index');
});

require __DIR__.'/auth.php';
