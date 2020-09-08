<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPayementStaffMastAddOtherAllowanceAmount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staff_payment_mast', function (Blueprint $table) {
            $table->float('other_allowance_amount', 9, 2)->default(0);
        });

        Schema::table('staff_main_mast', function (Blueprint $table) {
            $table->float('other_allowance_amount', 9, 2)->default(0);
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
            $table->dropColumn('other_allowance_amount');

        });
        Schema::table('staff_main_mast', function (Blueprint $table) {
            $table->dropColumn('other_allowance_amount');

        });
    }
}
