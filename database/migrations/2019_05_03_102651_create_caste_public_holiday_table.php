<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCastePublicHolidayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caste_public_holiday', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('caste_id');
            $table->foreign('caste_id')->references('id')->on('castes');
            $table->unsignedInteger('public_holiday_id');
            $table->foreign('public_holiday_id')->references('id')->on('public_holidays');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('caste_public_holiday');
    }
}
