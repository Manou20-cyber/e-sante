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
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rendezvous_id')->nullable()->constrained('rendezvous')->nullOnDelete();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('cabinet_id')->constrained('cabinets_optiques')->cascadeOnDelete();
            $table->foreignId('medecin_id')->constrained('users')->cascadeOnDelete();
            $table->dateTime('date');
            $table->string('type', 50)->default('bilan_visuel');
            $table->text('diagnostic')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('montant', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
