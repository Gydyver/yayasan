<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // being created on each session, while the teacher give point to student. In each submit process the data added on this table could be more than one. Based on the chapter point aspect data
        // QC before re-migrate 29 April 2023
        Schema::create('point_history', function (Blueprint $table) {
            $table->id();
            // $table->foreign('student_id')->references('id')->on('users');
            // $table->foreign('session_generated_id')->references('id')->on('session_generated');
            // $table->foreign('chapter_point_aspect_id')->references('id')->on('chapter_point_aspect'); // For data chapter point aspect data
            // $table->foreign('point_aspect_id')->references('id')->on('point_aspect'); // Being attached by code in php while this data saved
            $table->integer('student_id');
            $table->integer('session_generated_id');
            $table->integer('chapter_point_aspect_id'); // For data chapter point aspect data
            // $table->integer('point_aspect_id'); // Being attached by code in php while this data saved
            $table->integer('grade_id'); // Being attached by code in php while this data saved
            $table->integer('point'); // Form inputed value (range 1 to 100)
            $table->string('teacher_notes', 255);
            $table->softDeletes($column = 'deleted_at', $precision = 0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('point_history');
    }
};
