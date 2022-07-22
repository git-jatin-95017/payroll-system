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
        Schema::create('gs_raw_prices', function (Blueprint $table) {
            $table->id();
            $table->string('location_codes')->nullable();            
            $table->string('item_codes')->nullable(); 
            $table->string('zip_codes',  25)->nullable();           
            $table->string('product')->nullable();
            $table->float('price', 15, 2)->nullable();
            $table->string('currency_code', 10)->nullable();
            $table->float('amount', 15, 2)->nullable();
            $table->string('units', 20)->nullable();            
            $table->mediumText('website')->nullable();
            $table->string('store')->nullable();
            $table->string('store_address')->nullable();
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
        Schema::dropIfExists('gs_raw_prices');
    }
};
