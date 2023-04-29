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
        Schema::create('grade', function (Blueprint $table) {
            $table->id();
            $table->string('name',50);
            $table->integer('lowest_poin');
            $table->integer('highest_poin');
            $table->string('description',255);
            $table->string('notes',255);
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
        Schema::dropIfExists('grade');
    }
};
