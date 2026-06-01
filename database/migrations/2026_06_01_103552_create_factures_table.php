<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('factures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('cabinet_id')->constrained('cabinets_optiques')->cascadeOnDelete();
            $table->foreignId('commande_id')->nullable()->constrained('commandes')->nullOnDelete();
            $table->foreignId('consultation_id')->nullable()->constrained('consultations')->nullOnDelete();
            $table->string('numero')->unique();
            $table->decimal('montant_ht', 10, 2)->default(0);
            $table->decimal('taux_tva', 5, 2)->default(20.00);
            $table->decimal('montant_ttc', 10, 2)->default(0);
            $table->enum('statut', ['brouillon', 'emise', 'payee', 'annulee', 'remboursee'])->default('brouillon');
            $table->date('date_emission');
            $table->date('date_echeance')->nullable();
            $table->string('chemin_pdf')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factures');
    }
};
