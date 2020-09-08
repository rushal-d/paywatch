<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterVehicleLoanLogsAddSalaryMonth extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicle_loan_transaction_logs', function (Blueprint $table) {
            $table->integer('deduc_salary_month')->nullable();
            $table->integer('payroll_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicle_loan_transaction_logs', function (Blueprint $table) {
            //
        });
    }
}
