<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPayrollCalculationDatasAddHouseVehicleLoanInstallment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payroll_calculation_datas', function (Blueprint $table) {
            $table->float('house_loan_installment',10,2)->nullable();
            $table->float('vehicle_loan_installment',10,2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payroll_calculation_datas', function (Blueprint $table) {
            $table->dropColumn('house_loan_installment');
            $table->dropColumn('vehicle_loan_installment');
        });
    }
}
