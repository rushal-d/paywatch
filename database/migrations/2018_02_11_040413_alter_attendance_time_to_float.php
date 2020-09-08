<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAttendanceTimeToFloat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendance_details', function (Blueprint $table) {
            $table->float('total_work_hour', 3,2)->change();
            $table->float('total_ot_hour', 3,2)->change();
        });

	    Schema::table('attendance_details_sum', function (Blueprint $table) {
		    $table->float('total_work_hour', 3,2)->change();
		    $table->float('total_ot_hour', 3,2)->change();
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendance_details', function (Blueprint $table) {
	        Schema::table('attendance_details', function (Blueprint $table) {
		       /* $table->int('total_work_hour')->length(11)->change();
		        $table->int('total_ot_hour')->length(11)->change();*/
	        });

	        Schema::table('attendance_details_sum', function (Blueprint $table) {
		     /*   $table->int('total_work_hour')->length(11)->change();
		        $table->int('total_ot_hour')->length(11)->change();*/
	        });
        });
    }
}
