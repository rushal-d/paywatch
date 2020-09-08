<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPayrollConfirmAddOutstationAmountAndMaternityCareLeaveTaken extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payroll_confirms', function (Blueprint $table) {
            $table->integer('maternity_care_leave_taken')->default(0)->nullable()->after('maternity_leave_taken');
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
            $table->dropColumn('maternity_care_leave_taken');
        });
    }
}
