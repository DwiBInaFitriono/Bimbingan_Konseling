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
        Schema::create('case_studies', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->string('case_title');
            $table->text('case_description');
            $table->enum('case_type', ['pelanggaran', 'pribadi', 'sosial', 'belajar', 'karir'])->default('pelanggaran');
            $table->text('action_taken')->nullable();
            $table->text('recommendation')->nullable();
            $table->enum('status', ['proses', 'selesai', 'tindak_lanjut'])->default('proses');
            $table->foreignId('handled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->date('case_date');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_studies');
    }
};
