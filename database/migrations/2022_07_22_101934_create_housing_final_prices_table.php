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
        Schema::create('housing_final_prices', function (Blueprint $table) {
            $table->id();
            $table->string('location_codes')->nullable();            
            $table->string('housing_codes')->nullable();
            $table->string('price_type')->nullable();
            $table->string('house_type')->nullable();
            $table->integer('bedrooms')->nullable();  
            $table->string('price_level')->nullable();
            $table->float('price', 15, 2)->nullable();
            $table->string('currency', 10)->nullable();            
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
        Schema::dropIfExists('housing_final_prices');
    }
};
