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
        // It is generated
        Schema::create('billing', function (Blueprint $table) {
            $table->id();
            $table->integer('student_id');
            // $table->foreign('student_id')->references('id')->on('users');
            $table->integer('billing');
            $table->integer('month');
            $table->integer('year');
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
        Schema::dropIfExists('billing');
    }
};
