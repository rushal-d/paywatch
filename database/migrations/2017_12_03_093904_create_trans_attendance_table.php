<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransAttendanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trans_attendance', function (Blueprint $table) {
            $table->bigIncrements('trans_id');
            $table->bigInteger("staff_central_id")->unsigned();
            $table->foreign('staff_central_id')->references('id')->on('staff_main_mast')->onDelete('cascade');
            $table->string("office_id",7)->nullable();
            $table->date("date_ad")->nullable();
            $table->string("data_bs",10)->nullable();
            $table->double("work_hrs")->nullable();
            $table->double("salary_hrs")->nullable();
            $table->double("ot_hrs")->nullable();
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
        Schema::dropIfExists('trans_attendance');
    }
}
