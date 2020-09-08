<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransLeaveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trans_leave', function (Blueprint $table) {
            $table->bigIncrements('leave_id');
            $table->string("leave_title",50)->nullable();
            $table->bigInteger("staff_central_id")->unsigned();
            $table->foreign('staff_central_id')->references('id')->on('staff_main_mast')->onDelete('cascade');
            $table->date("trans_date")->nullable();
            $table->string("trans_date_np", 15)->nullable();
            $table->integer("deduct_day")->nullable();
            $table->integer("added_day")->nullable();
            $table->integer("balance_day")->nullable();
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
        Schema::dropIfExists('trans_leave');
    }
}
