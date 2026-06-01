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
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('cabinet_id')->constrained('cabinets_optiques')->cascadeOnDelete();
            $table->foreignId('ordonnance_id')->nullable()->constrained('ordonnances')->nullOnDelete();
            $table->string('numero')->unique();
            $table->enum('statut', ['en_attente', 'confirmee', 'en_preparation', 'prete', 'livree', 'annulee'])->default('en_attente');
            $table->decimal('montant_total', 10, 2)->default(0);
            $table->text('adresse_livraison')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};
