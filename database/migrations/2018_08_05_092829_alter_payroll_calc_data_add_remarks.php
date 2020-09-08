<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPayrollCalcDataAddRemarks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payroll_calculation_datas', function (Blueprint $table) {
            $table->text('remarks')->nullable()->after('misc_amount');
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
            $table->dropColumn('remarks');
        });
    }
}
