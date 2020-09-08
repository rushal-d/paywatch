<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPayrollConfirmsAddLevyAndLeaveInfos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payroll_confirms', function (Blueprint $table) {
            $table->float('levy_amount',9,2)->nullable();
            $table->integer('home_leave_taken')->nullable();
            $table->integer('sick_leave_taken')->nullable();
            $table->integer('maternity_leave_taken')->nullable();
            $table->integer('funeral_leave_taken')->nullable();
            $table->integer('substitute_leave_taken')->nullable();
            $table->integer('unpaid_leave_taken')->nullable();
            $table->integer('suspended_days')->nullable();
            $table->integer('useable_home_leave')->nullable();
            $table->integer('useable_sick_leave')->nullable();
            $table->integer('useable_substitute_leave')->nullable();
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
            $table->dropColumn('levy_amount');
            $table->dropColumn('home_leave_taken');
            $table->dropColumn('sick_leave_taken');
            $table->dropColumn('maternity_leave_taken');
            $table->dropColumn('funeral_leave_taken');
            $table->dropColumn('substitute_leave_taken');
            $table->dropColumn('unpaid_leave_taken');
            $table->dropColumn('suspended_days');
            $table->dropColumn('useable_home_leave');
            $table->dropColumn('useable_sick_leave');
            $table->dropColumn('useable_substitute_leave');
        });
    }
}
