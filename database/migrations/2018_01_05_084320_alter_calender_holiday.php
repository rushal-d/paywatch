<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCalenderHoliday extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('calender_holiday', function(Blueprint $table){
            $table->bigInteger("staff_central_id")->unsigned();
            $table->foreign('staff_central_id')->references('id')->on('staff_main_mast')->onDelete('cascade');
            $table->integer("leave_id")->unsigned();
            $table->foreign('leave_id')->references('leave_id')->on('system_leave_mast')->onDelete('cascade');
            $table->date("from_leave_day")->nullable();
            $table->date("to_leave_day")->nullable();
            $table->string("from_leave_day_np")->nullable();
            $table->string("to_leave_day_np")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('calender_holiday', function(Blueprint $table){
	        $table->dropForeign(['staff_central_id']);
            $table->dropColumn('staff_central_id');
	        $table->dropForeign(['leave_id']);
	        $table->dropColumn('leave_id');
            $table->dropColumn('from_leave_day_np');
            $table->dropColumn('to_leave_day');
            $table->dropColumn('from_leave_day');
            $table->dropColumn('to_leave_day_np');
        });
    }
}
