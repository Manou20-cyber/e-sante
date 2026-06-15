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
            $table->foreignId('opticien_id')->nullable()->after('cabinet_id')
                ->constrained('users')->nullOnDelete();
            $table->decimal('prix', 10, 0)->nullable()->after('opticien_id')
                ->comment('Prix consultation XAF');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('creneaux_horaires', function (Blueprint $table) {
            $table->dropForeign(['opticien_id']);
            $table->dropColumn(['opticien_id', 'prix']);
        });
    }
};
