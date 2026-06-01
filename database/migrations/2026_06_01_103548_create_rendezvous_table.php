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
        Schema::create('rendezvous', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('cabinet_id')->constrained('cabinets_optiques')->cascadeOnDelete();
            $table->foreignId('creneau_id')->nullable()->constrained('creneaux_horaires')->nullOnDelete();
            $table->dateTime('date');
            $table->unsignedSmallInteger('duree')->default(30)->comment('en minutes');
            $table->string('type', 50)->default('consultation');
            $table->enum('statut', ['en_attente', 'confirme', 'annule', 'termine', 'absent'])->default('en_attente');
            $table->text('motif')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rendezvous');
    }
};
