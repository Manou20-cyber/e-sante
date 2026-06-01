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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expediteur_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('destinataire_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('cabinet_id')->nullable()->constrained('cabinets_optiques')->nullOnDelete();
            $table->text('contenu');
            $table->string('objet', 200)->nullable();
            $table->timestamp('lu_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
