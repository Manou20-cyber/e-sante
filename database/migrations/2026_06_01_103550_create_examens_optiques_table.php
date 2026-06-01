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
        Schema::create('examens_optiques', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultation_id')->constrained('consultations')->cascadeOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->decimal('acuite_od', 4, 2)->nullable()->comment('Acuité visuelle OD sans correction');
            $table->decimal('acuite_og', 4, 2)->nullable();
            $table->decimal('acuite_od_corrigee', 4, 2)->nullable();
            $table->decimal('acuite_og_corrigee', 4, 2)->nullable();
            $table->decimal('tension_od', 5, 2)->nullable()->comment('Pression intraoculaire mmHg');
            $table->decimal('tension_og', 5, 2)->nullable();
            $table->json('resultats_complementaires')->nullable();
            $table->text('observations')->nullable();
            $table->string('chemin_fichier')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examens_optiques');
    }
};
