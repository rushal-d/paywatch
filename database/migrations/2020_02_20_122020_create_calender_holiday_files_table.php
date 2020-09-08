<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalenderHolidayFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calender_holiday_files', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('calender_holiday_id');
            $table->foreign('calender_holiday_id')->references('id')->on('calender_holiday')->onDelete('cascade');

            $table->unsignedInteger('staff_file_id')->onDelete('cascade');
            $table->foreign('staff_file_id')->references('id')->on('staff_file');

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
        Schema::dropIfExists('calender_holiday_files');
    }
}
