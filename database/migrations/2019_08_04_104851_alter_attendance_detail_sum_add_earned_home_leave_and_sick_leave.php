<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAttendanceDetailSumAddEarnedHomeLeaveAndSickLeave extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendance_details_sum', function (Blueprint $table) {
            $table->float('earned_home_leave',5,2)->default(0);
            $table->float('earned_sick_leave',5,2)->default(0);
            $table->float('earned_substitute_leave',5,2)->default(0);
            $table->integer('present_days')->default(0);
            $table->integer('absent_on_weekend')->default(0);
            $table->integer('absent_on_public_holiday')->default(0);
            $table->integer('absent_on_public_holiday_on_weekend')->default(0);
            $table->integer('absent_days')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendance_details_sum', function (Blueprint $table) {
            $table->dropColumn('earned_home_leave');
            $table->dropColumn('earned_sick_leave');
            $table->dropColumn('earned_substitute_leave');
            $table->dropColumn('present_days');
            $table->dropColumn('absent_on_weekend');
            $table->dropColumn('absent_on_public_holiday');
            $table->dropColumn('absent_on_public_holiday_on_weekend');
            $table->dropColumn('absent_days');
        });
    }
}
