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
        Schema::create('ordonnances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dossier_id')->constrained('dossiers_medicaux')->cascadeOnDelete();
            $table->foreignId('consultation_id')->nullable()->constrained('consultations')->nullOnDelete();
            $table->date('date');
            $table->decimal('sphere_od', 5, 2)->nullable()->comment('Oeil droit');
            $table->decimal('sphere_og', 5, 2)->nullable()->comment('Oeil gauche');
            $table->decimal('cylindre_od', 5, 2)->nullable();
            $table->decimal('cylindre_og', 5, 2)->nullable();
            $table->decimal('axe_od', 5, 2)->nullable();
            $table->decimal('axe_og', 5, 2)->nullable();
            $table->decimal('addition_od', 5, 2)->nullable();
            $table->decimal('addition_og', 5, 2)->nullable();
            $table->decimal('ecart_pupillaire', 5, 2)->nullable()->comment('en mm');
            $table->text('notes')->nullable();
            $table->string('chemin_pdf')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordonnances');
    }
};
