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
        // Penghubung Antara Chapter dan Point Aspect
        // QC before re-migrate 29 April 2023
        Schema::create('chapter_point_aspect', function (Blueprint $table) {
            $table->id();
            $table->integer('chapter_id');
            $table->integer('point_aspect_id');
            // $table->string('name', 50); //name dihilangkan aja, harus diubah di class diagram nya
            // $table->foreign('chapter_id')->references('id')->on('chapter');
            // $table->foreign('point_aspect_id')->references('id')->on('point_aspect');
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
        Schema::dropIfExists('chapter_point_aspect');
    }
};
