<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPayrollConfirmsAddSocialSecurityFundAmt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payroll_confirms', function (Blueprint $table) {
            $table->float('social_security_fund_amount')->default(0);
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
            $table->dropColumn('social_security_fund_amount');
        });
    }
}
