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
        //Generated Data
        // Always Created onces a student finish a class or go back to previous class
        // The start_date will be filled when data created, the end_date will be filled after the is a new data created
        // In the other hand, the end_date of previous data will be the same as start_date in the latest data

        // The data in this table, will be used to see each student timeperiod on a chapter and also make it possible to change chapter inside semester
        
        // QC before re-migrate 29 April 2023
        Schema::create('chapter_history', function (Blueprint $table) {
            $table->id();
            $table->integer('student_id');
            $table->integer('chapter_id');
            $table->date('start_date');
            $table->date('end_date')->nullable();
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
        Schema::dropIfExists('chapter_history');
    }
};
