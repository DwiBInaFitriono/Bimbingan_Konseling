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
        Schema::create('classdatas', function (Blueprint $table) {
            $table->id('id');
            $table->enum('grade', ['10', '11', '12'])->default('10');
            $table->string('school_class_name');
            $table->string('school_class_major');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classdatas');
    }
};
