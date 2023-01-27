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
        // Data Chapter
        // - Jilid I Anak
        // - Jilid II Anak
        // - Jilid III Anak
        // - Jilid IV Anak
        // - Jilid V Anak
        // - Jilid VI Anak
        // - Jilid I Dewasa
        // - Jilid II Dewasa
        // - Jilid III Dewasa
        // - Al-Quran Dewasa
        Schema::create('chapter', function (Blueprint $table) {
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
        Schema::dropIfExists('chapter');
    }
};
