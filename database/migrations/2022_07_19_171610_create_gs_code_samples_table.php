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
        Schema::create('gs_code_samples', function (Blueprint $table) {
            $table->id();
            $table->string('item_codes')->nullable();
            $table->string('master_item_codes')->nullable();     
            $table->string('final_item')->nullable();     
            $table->string('component_items')->nullable();     
            $table->string('category')->nullable();     
            $table->string('store_type')->nullable();     
            $table->text('details')->nullable();     
            $table->float('standard_amounts', 15, 2)->nullable(); 
            $table->string('standard_units', 20)->nullable();          
            $table->string('unit_type')->nullable();     
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
        Schema::dropIfExists('gs_code_samples');
    }
};
