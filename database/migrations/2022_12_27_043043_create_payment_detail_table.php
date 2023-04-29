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
        // QC before re-migrate 29 April 2023
        Schema::create('payment_detail', function (Blueprint $table) {
            $table->id();
            // $table->foreign('payment_id')->references('id')->on('payment');
            // $table->foreign('student_id')->references('id')->on('users');
            $table->integer('payment_id');
            $table->integer('student_id');
            $table->integer('nominal');
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
        Schema::dropIfExists('payment_detail');
    }
};
