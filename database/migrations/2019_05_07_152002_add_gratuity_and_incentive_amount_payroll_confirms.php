<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGratuityAndIncentiveAmountPayrollConfirms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payroll_confirms', function (Blueprint $table) {
            $table->double('gratuity_amount', 40, 2)->default(0)->after('extra_allowance');
            $table->double('incentive', 40, 2)->default(0)->after('gratuity_amount');
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
            $table->dropColumn('gratuity_amount');
            $table->dropColumn('incentive');
        });
    }
}
