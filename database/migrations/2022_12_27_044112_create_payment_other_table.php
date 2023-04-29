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
        Schema::create('payment_other', function (Blueprint $table) {
            $table->id();
            $table->integer('payment_id');
            // $table->foreign('payment_id')->references('id')->on('payment');
            $table->integer('nominal');
            $table->string('notes', 200)->nullable();
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
        Schema::dropIfExists('payment_other');
    }
};
