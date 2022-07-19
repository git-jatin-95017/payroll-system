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
        Schema::create('supermarket_samples', function (Blueprint $table) {
            $table->id();
            $table->string('location_codes')->nullable();            
            $table->string('item_codes')->nullable(); 
            $table->bigInteger('item_id')->nullable(); 
            $table->string('postal_code',  25)->nullable();           
            $table->mediumText('url')->nullable();
            $table->string('name')->nullable();
            $table->float('price', 15, 2)->nullable();
            $table->string('currency', 10)->nullable();
            $table->mediumText('source')->nullable();
            $table->string('number_of_units', 20)->nullable();            
            $table->string('final_units', 20)->nullable();
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
        Schema::dropIfExists('supermarket_samples');
    }
};
