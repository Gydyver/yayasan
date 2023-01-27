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
        //The reason why the teacher_id is placed here, because it is possible that a teacher being subtituted by other other when the main teacher can't teach at the time
        Schema::create('session_generated', function (Blueprint $table) {
            $table->id();
            // $table->foreign('session_id')->references('id')->on('session');
            // $table->foreign('teacher_id')->references('id')->on('users');
            $table->integer('session_id');
            $table->integer('teacher_id');
            $table->dateTime('session_start');
            $table->dateTime('session_end');
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
        Schema::dropIfExists('session_generated');
    }
};
