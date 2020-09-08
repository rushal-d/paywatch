<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPayrollConfirmDataTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payroll_calculation_datas', function (Blueprint $table) {
            $table->float('redeem_home_leave', 6, 2)->change();
            $table->float('redeem_sick_leave', 6, 2)->change();
            $table->integer('prepared_by');
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
            $table->dropColumn('prepared_by');
        });
    }
}
