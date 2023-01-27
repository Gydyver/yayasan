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
        //Master
        //Data Kelas
        // Always added every 6 months
        // The closed class would not be able to be changed, only become an history
        Schema::create('class', function (Blueprint $table) {
            $table->id();
            
            // $table->unsignedInteger('teacher_id');
            // $table->unsignedInteger('chapter_id');
            // $table->unsignedInteger('class_type_id');
            $table->string('name', 50);
            // $table->foreign('teacher_id')->references('id')->on('users');
            // $table->foreign('chapter_id')->references('id')->on('chapter');
            // $table->foreign('class_type_id')->references('id')->on('class_type');
            $table->integer('teacher_id');
            $table->integer('chapter_id');
            $table->integer('class_type_id');
            $table->boolean('closed');
            $table->date('class_start');
            $table->date('class_end');
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
        Schema::dropIfExists('class');
    }
};
