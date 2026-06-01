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
        Schema::create('documents_medicaux', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('dossier_id')->nullable()->constrained('dossiers_medicaux')->nullOnDelete();
            $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
            $table->string('nom');
            $table->enum('type', ['ordonnance', 'resultat', 'certificat', 'facture', 'autre']);
            $table->string('chemin_fichier');
            $table->string('mime_type', 100)->nullable();
            $table->unsignedBigInteger('taille')->nullable()->comment('en octets');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents_medicaux');
    }
};
