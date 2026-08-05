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
        Schema::table('counseling_sessions', function (Blueprint $table) {
            $table->time('available_time_start')->nullable()->after('slot_waktu');
            $table->time('available_time_end')->nullable()->after('available_time_start');
            $table->time('estimated_time')->nullable()->after('available_time_end');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('counseling_sessions', function (Blueprint $table) {
            $table->dropColumn(['available_time_start', 'available_time_end', 'estimated_time']);
        });
    }
};
