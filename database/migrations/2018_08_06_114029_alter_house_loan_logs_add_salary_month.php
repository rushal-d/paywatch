<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterHouseLoanLogsAddSalaryMonth extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('house_loan_transaction_logs', function (Blueprint $table) {
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
        Schema::table('house_loan_transaction_logs', function (Blueprint $table) {
            //
        });
    }
}
