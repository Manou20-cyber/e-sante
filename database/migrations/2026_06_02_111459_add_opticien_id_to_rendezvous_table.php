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
            $table->foreignId('opticien_id')->nullable()->after('creneau_id')
                ->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rendezvous', function (Blueprint $table) {
            $table->foreignId('opticien_id')->nullable()->after('creneau_id')
                ->constrained('users')->nullOnDelete();
        });
    }
};
