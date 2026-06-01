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
        Schema::create('cabinets_optiques', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nom');
            $table->text('adresse');
            $table->string('ville', 100);
            $table->string('code_postal', 10);
            $table->string('telephone', 20);
            $table->string('email')->nullable();
            $table->string('siret', 20)->nullable();
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->boolean('est_actif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cabinets_optiques');
    }
};
