<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeavePayrollConfirmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_payroll_confirms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('payroll_confirm_id')->unsigned();
            $table->foreign('payroll_confirm_id')
                ->references('id')->on('payroll_confirms')
                ->onDelete('cascade');
            $table->integer('home_leave_taken')->nullable();
            $table->integer('sick_leave_taken')->nullable();
            $table->integer('maternity_leave_taken')->nullable();
            $table->integer('funeral_leave_taken')->nullable();
            $table->integer('substitute_leave_taken')->nullable();
            $table->integer('unpaid_leave_taken')->nullable();
            $table->integer('suspended_days')->nullable();
            $table->integer('redeem_home_leave')->nullable();
            $table->integer('redeem_sick_leave')->nullable();
            $table->integer('useable_home_leave')->nullable();
            $table->integer('useable_sick_leave')->nullable();
            $table->integer('useable_substitute_leave')->nullable();
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
        Schema::dropIfExists('leave_payroll_confirms');
    }
}
