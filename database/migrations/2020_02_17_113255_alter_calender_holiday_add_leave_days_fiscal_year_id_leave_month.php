<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCalenderHolidayAddLeaveDaysFiscalYearIdLeaveMonth extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('calender_holiday', function (Blueprint $table) {
            $table->unsignedInteger('fiscal_year_id')->after('id')->nullable();
            $table->foreign('fiscal_year_id')->references('id')->on('fiscal_year');
            $table->float('leave_days')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('calender_holiday', function (Blueprint $table) {
            $table->dropForeign(['fiscal_year_id']);
            $table->dropColumn('fiscal_year_id');
            $table->dropColumn('leave_days');
        });
    }
}
