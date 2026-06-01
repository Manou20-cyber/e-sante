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
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cabinet_id')->constrained('cabinets_optiques')->cascadeOnDelete();
            $table->string('libelle');
            $table->text('description')->nullable();
            $table->string('reference', 50)->nullable();
            $table->decimal('prix', 10, 2);
            $table->unsignedInteger('stock')->default(0);
            $table->unsignedInteger('stock_alerte')->default(5);
            $table->enum('categorie', ['monture', 'lentille', 'verre', 'accessoire', 'autre']);
            $table->string('marque', 100)->nullable();
            $table->json('images')->nullable();
            $table->boolean('est_actif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};
