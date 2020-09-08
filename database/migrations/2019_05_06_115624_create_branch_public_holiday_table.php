<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBranchPublicHolidayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_public_holiday', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('branch_id');
            $table->foreign('branch_id')->references('office_id')->on('system_office_mast');
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
        Schema::dropIfExists('branch_public_holiday');
    }
}
