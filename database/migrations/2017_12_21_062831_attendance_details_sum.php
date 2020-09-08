<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AttendanceDetailsSum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create('attendance_details_sum', function (Blueprint $table) {
		    $table->bigIncrements('id');
		    $table->bigInteger("payroll_id")->nullable();
		    $table->bigInteger("staff_central_id")->unsigned();
            $table->foreign('staff_central_id')->references('id')->on('staff_main_mast')->onDelete('cascade');
		    $table->integer("branch_id")->nullable();
		    $table->integer("weekend_holiday")->length(11)->nullable();
		    $table->integer("public_holiday")->length(11)->nullable();
		    $table->integer("total_work_hour")->length(11)->nullable();
		    $table->integer("total_ot_hour")->length(11)->nullable();
		    $table->date("date")->nullable();
		    $table->string("date_np",15)->nullable();
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
        Schema::dropIfExists('attendance_details_sum');
    }
}
