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
        Schema::table('rendezvous', function (Blueprint $table) {
            $table->boolean('demande_video')->default(false)->after('statut');
        });
    }

    public function down(): void
    {
        Schema::table('rendezvous', function (Blueprint $table) {
            $table->dropColumn('demande_video');
        });
    }
};
