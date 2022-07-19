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
        Schema::create('expenditure_samples', function (Blueprint $table) {
            $table->id();
            $table->string('country')->nullable();   
            $table->string('currency', 10)->nullable();                     
            $table->string('type')->nullable();           
            $table->integer('num_of_adult')->nullable(); 
            $table->integer('num_of_child')->nullable(); 
            $table->float('coefficient_a', 10, 6)->nullable();              
            $table->float('coefficient_b', 10, 6)->nullable(); 
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
        Schema::dropIfExists('expenditure_samples');
    }
};
