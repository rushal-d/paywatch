<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRedeemLeavesToFloat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leave_payroll_confirms', function (Blueprint $table) {
            $table->float('redeem_home_leave', 6, 2)->change();
            $table->float('redeem_sick_leave', 6, 2)->change();
        });

        Schema::table('payroll_confirms', function (Blueprint $table) {
            $table->float('redeem_home_leave', 6, 2)->change();
            $table->float('redeem_sick_leave', 6, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leave_payroll_confirms,payroll_confirms', function (Blueprint $table) {
            //
        });
    }
}
