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
        Schema::create('location_codes', function (Blueprint $table) {
            $table->id();
            $table->string('location_codes')->unique();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('postal_code',  25)->nullable();
            $table->string('city_province')->nullable();
            $table->string('country')->nullable();
            $table->string('country_code', 10)->nullable();
            $table->string('province_code', 10)->nullable();
            $table->string('metropolitan_codes', 10)->nullable();
            $table->string('sub_metropolitan_codes', 10)->nullable();
            $table->string('region')->nullable();
            $table->string('iso_3166_alpha_2')->nullable();
            $table->string('iso_3166_alpha_3')->nullable();
            $table->string('iso_4217_currency_name')->nullable();
            $table->string('iso_4217_alphabetic_Codes')->nullable();
            $table->string('iso_4217_numeric_Codes')->nullable();
            $table->string('tax_codes')->nullable();
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
        Schema::dropIfExists('location_codes');
    }
};
