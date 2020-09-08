<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicHolidayReligionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('public_holiday_religion', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('public_holiday_id');
            $table->foreign('public_holiday_id')->references('id')->on('public_holidays');
            $table->unsignedInteger('religion_id');
            $table->foreign('religion_id')->references('id')->on('religions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('public_holiday_religion');
    }
}
