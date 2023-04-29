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
        // Master
        // Data Session
        // QC before re-migrate 29 April 2023
        Schema::create('session', function (Blueprint $table) {
            $table->id();
            $table->integer('class_id');
            // $table->foreign('class_id')->references('id')->on('class');
            $table->string('name', 50);
            $table->string('day', 50);
            $table->string('time', 50);
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
        Schema::dropIfExists('session');
    }
};
