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
        //Data Menu
        //QC before re-migrate 29 April 2023
        Schema::create('menu', function (Blueprint $table) {
            $table->id();
            $table->integer('menuparent_id')->nullable();
            $table->integer('order')->nullable();//belum ada di classdiagram
            $table->string('name', 50);
            $table->string('url', 50);
            $table->string('icon', 15);
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
        Schema::dropIfExists('menu');
    }
};
