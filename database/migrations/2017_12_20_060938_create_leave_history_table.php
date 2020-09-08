<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeaveHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date("year_month")->nullable();
            $table->date("year_month_np")->nullable();
            $table->bigInteger("staff_central_id")->unsigned();
            $table->foreign('staff_central_id')->references('id')->on('staff_main_mast')->onDelete('cascade');
            $table->tinyInteger("public_holidays_consumption")->length(11)->nullable();
            $table->tinyInteger("weekend_holidays_consumption")->length(11)->nullable();
            $table->tinyInteger("public_holiday_earned")->length(11)->nullable();
            $table->tinyInteger("weekend_holiday_earned")->length(11)->nullable();
            $table->tinyInteger("public_holiday_balance")->length(11)->nullable();
            $table->tinyInteger("weekend_holiday_balance")->length(11)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leave_history');
    }
}
