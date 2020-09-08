<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPayrollConfirmsChangeIntToFloat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payroll_confirms', function (Blueprint $table) {
            $table->float('salary_hour_payable', 6, 2)->change();
            $table->float('ot_hour_payable', 6, 2)->change();
            $table->float('home_leave_taken', 6, 2)->change();
            $table->float('sick_leave_taken', 6, 2)->change();
            $table->float('maternity_leave_taken', 6, 2)->change();
            $table->float('funeral_leave_taken', 6, 2)->change();
            $table->float('substitute_leave_taken', 6, 2)->change();
            $table->float('unpaid_leave_taken', 6, 2)->change();
            $table->float('suspended_days', 6, 2)->change();
            $table->float('useable_home_leave', 6, 2)->change();
            $table->float('useable_sick_leave', 6, 2)->change();
            $table->float('useable_substitute_leave', 6, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payroll_confirms', function (Blueprint $table) {
            $table->integer('salary_hour_payable')->change();
            $table->integer('ot_hour_payable')->change();
            $table->integer('home_leave_taken')->change();
            $table->integer('sick_leave_taken')->change();
            $table->integer('maternity_leave_taken')->change();
            $table->integer('funeral_leave_taken')->change();
            $table->integer('substitute_leave_taken')->change();
            $table->integer('unpaid_leave_taken')->change();
            $table->integer('suspended_days')->change();
            $table->integer('useable_home_leave')->change();
            $table->integer('useable_sick_leave')->change();
            $table->integer('useable_substitute_leave')->change();
        });
    }
}
