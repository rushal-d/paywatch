<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterStaffPayementAddFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staff_payment_mast', function (Blueprint $table) {
            $table->renameColumn('dasai_allow','dashain_allow');
            $table->renameColumn('gratu_mode','gratuity_allow');
            $table->float('outstation_facility_amount', 9, 2)->nullable();
            $table->float('special_allowance_amount', 9, 2)->nullable();
            $table->float('special_allowance_2_amount', 9, 2)->nullable();
            $table->float('risk_allowance_amount', 9, 2)->nullable();
            $table->float('dearness_allowance_amount', 9, 2)->nullable();
            $table->float('gratuity_allowance_amount', 9, 2)->nullable();
            $table->float('profund_allowance_amount', 9, 2)->nullable();
            $table->float('extra_allowance_amount', 9, 2)->nullable();
            $table->tinyInteger("special_allow_2")->nullable();
            $table->tinyInteger("outstation_facility_allow")->nullable();
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
            $table->renameColumn('dashain_allow','dasai_allow');
            $table->renameColumn('gratuity_allow','gratu_mode');
            $table->dropColumn('outstation_facility_amount')->nullable();
            $table->dropColumn('special_allowance_amount')->nullable();
            $table->dropColumn('special_allowance_2_amount')->nullable();
            $table->dropColumn('risk_allowance_amount')->nullable();
            $table->dropColumn('dearness_allowance_amount')->nullable();
            $table->dropColumn('gratuity_allowance_amount')->nullable();
            $table->dropColumn('profund_allowance_amount')->nullable();
            $table->dropColumn('extra_allowance_amount')->nullable();
            $table->dropColumn("special_allow_2")->nullable();
            $table->dropColumn("outstation_facility_allow")->nullable();
        });
    }
}
