<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PayrollCalculationDatasAddGrantLeaves extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payroll_calculation_datas', function (Blueprint $table) {
            $table->integer('grant_home_leave')->default(0);
            $table->integer('grant_sick_leave')->default(0);
            $table->integer('grant_substitute_leave')->default(0);
            $table->integer('grant_maternity_leave')->default(0);
            $table->integer('grant_maternity_care_leave')->default(0);
            $table->integer('grant_funeral_leave')->default(0);
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
            $table->dropColumn('grant_home_leave');
            $table->dropColumn('grant_sick_leave');
            $table->dropColumn('grant_substitute_leave');
            $table->dropColumn('grant_maternity_leave');
            $table->dropColumn('grant_maternity_care_leave');
            $table->dropColumn('grant_funeral_leave');
        });
    }
}
