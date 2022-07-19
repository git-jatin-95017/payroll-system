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
        Schema::create('housing_samples', function (Blueprint $table) {
            $table->id();
            $table->string('location_codes')->nullable();            
            $table->mediumText('source')->nullable();
            $table->mediumText('url')->nullable();
            $table->string('price_type')->nullable();
            $table->string('house_type')->nullable();
            $table->float('price', 15, 2)->nullable();
            $table->string('currency', 10)->nullable();
            $table->integer('bedrooms')->nullable();  
            $table->integer('bathrooms')->nullable();  
            $table->integer('size')->nullable();  
            $table->string('size_units', 55)->nullable();
            $table->string('address')->nullable();
            $table->string('housing_codes')->nullable();
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
        Schema::dropIfExists('housing_samples');
    }
};
