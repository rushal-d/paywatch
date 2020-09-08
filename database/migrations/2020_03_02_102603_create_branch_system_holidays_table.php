<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBranchSystemHolidaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_system_holidays', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('branch_id');
            $table->foreign('branch_id')->references('office_id')->on('system_office_mast');

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
        Schema::dropIfExists('branch_system_holidays');
    }
}
