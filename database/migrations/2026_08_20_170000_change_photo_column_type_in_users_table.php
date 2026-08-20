<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            DB::statement("ALTER TABLE users MODIFY photo LONGTEXT NULL");
        } catch (\Throwable $e) {
            Schema::table('users', function (Blueprint $table) {
                $table->longText('photo')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            DB::statement("ALTER TABLE users MODIFY photo VARCHAR(255) NULL");
        } catch (\Throwable $e) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('photo', 255)->nullable()->change();
            });
        }
    }
};
