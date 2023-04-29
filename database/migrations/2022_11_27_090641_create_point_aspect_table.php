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
        // Isinya
        // - Makhroj
        // - Kelancaran 
        // - Gunnah
        // - Panjang Pendek
        // QC before re-migrate 29 April 2023
        Schema::create('point_aspect', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
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
        Schema::dropIfExists('point_aspect');
    }
};
