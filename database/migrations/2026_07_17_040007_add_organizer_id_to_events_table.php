<?php
// database/migrations/2026_07_17_000002_add_organizer_id_to_events_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // nullable karena event lama belum punya organizer
            $table->foreignId('organizer_id')->nullable()->after('category_id')
                ->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropConstrainedForeignId('organizer_id');
        });
    }
};