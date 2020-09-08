<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPaymentAddDashainAllowanceAmountColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staff_payment_mast', function (Blueprint $table) {
            $table->float('dashain_allowance_amount',9,2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('staff_payment_mast', function (Blueprint $table) {
            $table->dropColumn('dashain_allowance_amount');

        });
    }
}
