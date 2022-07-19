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
        Schema::create('national_samples', function (Blueprint $table) {
            $table->id();
            $table->string('location_codes')->nullable();            
            $table->string('item_codes')->nullable(); 
            $table->string('product')->nullable();
            $table->float('price', 15, 2)->nullable();
            $table->string('currency', 10)->nullable();
            $table->mediumText('website')->nullable();
            $table->string('store')->nullable();
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('national_samples');
    }
};
