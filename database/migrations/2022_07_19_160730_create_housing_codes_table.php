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
        Schema::create('housing_codes', function (Blueprint $table) {
            $table->id();
            $table->string('item_codes')->unique();
            $table->string('price_type')->nullable();
            $table->string('housing_type')->nullable();
            $table->integer('bedroom_size')->nullable();          
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
        Schema::dropIfExists('housing_codes');
    }
};
