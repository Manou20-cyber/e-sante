<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE paiements MODIFY methode ENUM('carte','especes','virement','cheque','mutuelle','mobile_money') DEFAULT 'carte'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE paiements MODIFY methode ENUM('carte','especes','virement','cheque','mutuelle') DEFAULT 'carte'");
    }
};
