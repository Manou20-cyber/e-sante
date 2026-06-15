<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // La colonne uuid existe déjà avec des valeurs — on la passe juste NOT NULL
        Schema::table('cabinets_optiques', function (Blueprint $table) {
            $table->uuid('uuid')->nullable(false)->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('cabinets_optiques', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
