<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReligionSystemHolidayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('religion_system_holiday', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('religion_id');
            $table->foreign('religion_id')->references('id')->on('religions');

            $table->unsignedInteger('system_holiday_id');
            $table->foreign('system_holiday_id')->references('holiday_id')->on('system_holiday_mast');
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
        Schema::dropIfExists('religion_system_holiday');
    }
}
