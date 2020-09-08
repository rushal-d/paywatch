<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AttendanceDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create('attendance_details', function (Blueprint $table) {
		    $table->bigIncrements('id');
		    $table->bigInteger("staff_central_id")->unsigned();
            $table->foreign('staff_central_id')->references('id')->on('staff_main_mast')->onDelete('cascade');
		    $table->date("date")->nullable();
		    $table->bigInteger("payroll_id")->nullable();
		    $table->string("date_np",15)->nullable();
		    $table->integer("weekend_holiday")->length(11)->nullable();
		    $table->integer("public_holiday")->length(11)->nullable();
		    $table->integer("total_work_hour")->length(11)->nullable();
		    $table->integer("total_ot_hour")->length(11)->nullable();
		    $table->string("status",5)->length(11)->nullable();
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
        Schema::dropIfExists('attendance_details');
    }
}
