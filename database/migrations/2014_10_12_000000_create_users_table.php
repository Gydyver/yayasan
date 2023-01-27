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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 30);
            $table->integer('usergroup_id');
            // $table->foreign('usergroup_id')->references('id')->on('usergroup');
            $table->string('phone', 15)->nullable();
            $table->string('username', 30);
            $table->string('password');
            $table->integer('monthly_fee');
            $table->string('gender',20);
            $table->date('birth_date');
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
