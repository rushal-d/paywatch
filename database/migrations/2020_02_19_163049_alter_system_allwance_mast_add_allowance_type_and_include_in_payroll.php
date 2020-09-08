<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSystemAllwanceMastAddAllowanceTypeAndIncludeInPayroll extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('system_allwance_mast', function (Blueprint $table) {
            $table->integer('allowance_type')->default(1);
            $table->boolean('include_in_payroll')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('system_allwance_mast', function (Blueprint $table) {
            $table->dropColumn('allowance_type');
            $table->dropColumn('include_in_payroll');
        });
    }
}
