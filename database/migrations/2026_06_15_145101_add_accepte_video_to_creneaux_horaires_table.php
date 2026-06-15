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
        Schema::table('creneaux_horaires', function (Blueprint $table) {
            $table->boolean('accepte_video')->default(false)->after('est_actif');
        });
    }

    public function down(): void
    {
        Schema::table('creneaux_horaires', function (Blueprint $table) {
            $table->dropColumn('accepte_video');
        });
    }
};
