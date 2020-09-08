<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalenderHolidaySplitMonthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calender_holiday_split_months', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('calender_holiday_id');
            $table->foreign('calender_holiday_id')->references('id')->on('calender_holiday')->onDelete('cascade');
            $table->unsignedInteger('fiscal_year_id')->nullable();
            $table->foreign('fiscal_year_id')->references('id')->on('fiscal_year');
            $table->integer('leave_month');
            $table->float('leave_days');
            $table->softDeletes();
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
        Schema::dropIfExists('calender_holiday_split_months');
    }
}
