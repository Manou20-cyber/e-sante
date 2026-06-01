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
        Schema::create('creneaux_horaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cabinet_id')->constrained('cabinets_optiques')->cascadeOnDelete();
            $table->tinyInteger('jour_semaine')->comment('1=Lundi, 7=Dimanche');
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->unsignedSmallInteger('duree_consultation')->default(30)->comment('en minutes');
            $table->unsignedSmallInteger('capacite_max')->default(1);
            $table->boolean('est_actif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('creneaux_horaires');
    }
};
