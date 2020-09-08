<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableLeaveBalance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_balance', function (Blueprint $table) {
            $table->bigIncrements('id');
	        $table->bigInteger('staff_central_id');
	        $table->tinyInteger('leave_id');
	        $table->integer('fy_id');
	        $table->string('description', 255);
	        $table->integer('consumption');
	        $table->integer('earned');
	        $table->integer('balance');
	        $table->integer('authorized_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('leave_balance');
    }
}
