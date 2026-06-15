<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rendezvous', function (Blueprint $table) {
            $table->string('video_room')->nullable()->unique()->after('statut');
            $table->timestamp('video_started_at')->nullable()->after('video_room');
        });
    }

    public function down(): void
    {
        Schema::table('rendezvous', function (Blueprint $table) {
            $table->dropColumn(['video_room', 'video_started_at']);
        });
    }
};
