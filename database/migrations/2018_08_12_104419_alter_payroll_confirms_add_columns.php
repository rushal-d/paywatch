<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPayrollConfirmsAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payroll_confirms', function (Blueprint $table) {
            $table->float('total_worked_hours', 6, 2)->after('tax_code');
            $table->integer('days_absent_on_holiday')->after('total_worked_hours')->nullable();
            $table->float('weekend_work_hours', 6, 2)->after('days_absent_on_holiday')->nullable();
            $table->float('public_holiday_work_hours', 6, 2)->after('weekend_work_hours')->nullable();

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
            $table->dropColumn('total_worked_hours');
            $table->dropColumn('days_absent_on_holiday');
            $table->dropColumn('weekend_work_hours');
            $table->dropColumn('public_holiday_work_hours');
        });
    }
}
