<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffWorkscheduleMastTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_workschedule_mast', function (Blueprint $table) {
            $table->bigIncrements('work_id');
            $table->bigInteger("staff_central_id")->unsigned();
            $table->foreign('staff_central_id')->references('id')->on('staff_main_mast')->onDelete('cascade');
            $table->float("work_hour")->nullable();
            $table->string("weekend_day",2)->nullable();
            $table->date("effect_day")->nullable();
            $table->string("effect_date_np")->nullable();
            $table->string("work_status",45)->nullable();
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
        Schema::dropIfExists('staff_workschedule_mast');
    }
}
