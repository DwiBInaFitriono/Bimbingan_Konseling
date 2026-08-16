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
        Schema::table('case_studies', function (Blueprint $table) {
            $table->string('reporter_teacher')->nullable()->after('status');
            $table->string('subject_name')->nullable()->after('reporter_teacher');
            $table->string('time_of_occurrence')->nullable()->after('subject_name');
            $table->integer('points_sanction')->nullable()->after('time_of_occurrence');
            $table->boolean('points_applied')->default(false)->after('points_sanction');
            $table->string('evidence_file')->nullable()->after('points_applied');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('case_studies', function (Blueprint $table) {
            $table->dropColumn([
                'reporter_teacher',
                'subject_name',
                'time_of_occurrence',
                'points_sanction',
                'points_applied',
                'evidence_file'
            ]);
        });
    }
};
