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
        Schema::create('data_point_categories', function (Blueprint $table) {
            $table->id('id');
            $table->string('category_of_violation');
            $table->integer('category_score_min');
            $table->integer('category_score_max');
            $table->string('follow_up');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_point_categories');
    }
};
