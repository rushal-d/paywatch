<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayrollConfirmLeaveInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payroll_confirm_leave_infos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('payroll_confirm_id');
            $table->unsignedInteger('leave_id');
            $table->foreign('leave_id')->references('leave_id')->on('system_leave_mast');
            $table->float('used', 8, 2)->default(0);
            $table->float('earned', 8, 2)->default(0);
            $table->float('balance', 8, 2)->default(0);
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
        Schema::dropIfExists('payroll_confirm_leave_infos');
    }
}
