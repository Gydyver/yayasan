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
        Schema::create('payment', function (Blueprint $table) {
            $table->id();
            // $table->integer('billing_id');//untuk catatan, pindah ketebel payment_detail
            // $table->foreign('billing_id')->references('id')->on('billing');
            $table->date('transfer_time')->nullable();
            $table->string('link_prove')->nullable();
            $table->integer('payment_billing')->nullable();
            $table->string('notes')->nullable(); // Untuk catatan,ada transaksi yang terhubung atau nggak
            $table->boolean('verified')->default(0);
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
        Schema::dropIfExists('payment');
    }
};
