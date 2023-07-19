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
        //Data User Peserta Didik and Staff
        //QC before re-migrate 29 April 2023
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 30);
            $table->integer('usergroup_id');
            $table->integer('chapter_id')->nullable(); //only for user student//belum ada di classdiagram
            $table->integer('class_id')->nullable(); //only for user student//belum ada di classdiagram
            // $table->foreign('usergroup_id')->references('id')->on('usergroup');
            $table->string('phone', 15)->nullable();
            $table->string('username', 30)->unique();;
            $table->string('password');
            $table->integer('monthly_fee')->nullable();
            $table->string('gender', 20);
            $table->date('birth_date');
            $table->date('join_date');
            $table->string('latest_hapalan')->nullable();
            $table->string('latest_halaman')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
