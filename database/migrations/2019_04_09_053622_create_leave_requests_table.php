<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeaveRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('staff_central_id');
            $table->tinyInteger('leave_id');
            $table->integer('fy_id');
            $table->integer('authorized_by');
            $table->integer('leave_balance');
            $table->string('description', 255);
            $table->string("from_leave_day_np")->nullable();
            $table->string("from_leave_day")->nullable();
            $table->string("to_leave_day_np")->nullable();
            $table->string("to_leave_day")->nullable();
            $table->string("public_holidays")->nullable();
            $table->string("weekend_days")->nullable();
            $table->string("public_weekend")->nullable();
            $table->string("holiday_days")->nullable();
            $table->unsignedInteger('status');
            $table->softDeletes();
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
        Schema::dropIfExists('leave_requests');
    }
}
