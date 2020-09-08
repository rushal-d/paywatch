<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayrollConfirmAllowancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('leave_payroll_confirms');
        Schema::table('payroll_confirms', function (Blueprint $table) {
            $table->bigIncrements('id')->change();
        });
        Schema::create('payroll_confirm_allowances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('payroll_confirm_id');
            $table->unsignedInteger('allow_id');
            $table->foreign('allow_id')->references('allow_id')->on('system_allwance_mast');
            $table->double('amount', 12, 2)->default(0);
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
        Schema::dropIfExists('payroll_confirm_allowances');

        Schema::create('leave_payroll_confirms', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('payroll_confirm_id')->nullable();
            $table->float('home_leave_taken', 6, 2)->nullable();
            $table->float('sick_leave_taken', 6, 2)->nullable();
            $table->float('maternity_leave_taken', 6, 2)->nullable();
            $table->float('funeral_leave_taken', 6, 2)->nullable();
            $table->float('substitute_leave_taken', 6, 2)->nullable();
            $table->float('unpaid_leave_taken', 6, 2)->nullable();
            $table->float('suspended_days', 6, 2)->nullable();
            $table->float('redeem_home_leave', 6, 2)->change();
            $table->float('redeem_sick_leave', 6, 2)->change();
            $table->float('useable_home_leave', 6, 2)->nullable();
            $table->float('useable_sick_leave', 6, 2)->nullable();
            $table->float('useable_substitute_leave', 6, 2)->nullable();
            $table->timestamps();
        });
    }
}
