<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Kolom check_in_status bernilai default 'Unused'
            $table->enum('check_in_status', ['Unused', 'Used'])->default('Unused')->after('status');
            $table->timestamp('checked_in_at')->nullable()->after('check_in_status');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['check_in_status', 'checked_in_at']);
        });
    }
};