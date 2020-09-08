<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterStaffPaymentMast extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staff_payment_mast', function (Blueprint $table) {
            $table->dropColumn("pay_type");
            $table->dropColumn("account");
            $table->dropColumn("dear_allow");
            $table->dropColumn("risk_allow");
            $table->dropColumn("extra_allow");
            $table->dropColumn("other_allow");
            $table->dropColumn("dashain_allow");
            $table->dropColumn("special_allow");
            $table->dropColumn("gratuity_allow");
            $table->dropColumn("outstation_facility_amount");
            $table->dropColumn("special_allowance_amount");
            $table->dropColumn("special_allowance_2_amount");
            $table->dropColumn("risk_allowance_amount");
            $table->dropColumn("dearness_allowance_amount");
            $table->dropColumn("gratuity_allowance_amount");
            $table->dropColumn("profund_allowance_amount");
            $table->dropColumn("extra_allowance_amount");
            $table->dropColumn("dashain_allowance_amount");
            $table->dropColumn("other_allowance_amount");
            $table->dropColumn("special_allow_2");
            $table->dropColumn("outstation_facility_allow");

            $table->unsignedInteger('allow_id');
            $table->foreign('allow_id')->references('allow_id')->on('system_allwance_mast');
            $table->boolean('allow');
            $table->float('amount',20,2)->nullable();
            $table->date('effective_from')->nullable();
            $table->text('effective_from_np')->nullable();
            $table->date('effective_to')->nullable();
            $table->text('effective_to_np')->nullable();
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
            $table->dropForeign(['allow_id']);
            $table->dropColumn('allow_id');
            $table->dropColumn('allow');
            $table->dropColumn('amount')->nullable();
            $table->dropColumn('effective_from')->nullable();
            $table->dropColumn('effective_from_np')->nullable();
            $table->dropColumn('effective_to')->nullable();
            $table->dropColumn('effective_to_np')->nullable();

            $table->integer("pay_type")->nullable();
            $table->string("account")->nullable();
            $table->integer("dear_allow")->nullable();
            $table->integer("risk_allow")->nullable();
            $table->integer("extra_allow")->nullable();
            $table->integer("other_allow")->nullable();
            $table->integer("dashain_allow")->nullable();
            $table->integer("special_allow")->nullable();
            $table->integer("gratuity_allow")->nullable();
            $table->float("outstation_facility_amount",9,2)->nullable();
            $table->float("special_allowance_amount",9,2)->nullable();
            $table->float("special_allowance_2_amount",9,2)->nullable();
            $table->float("risk_allowance_amount",9,2)->nullable();
            $table->float("dearness_allowance_amount",9,2)->nullable();
            $table->float("gratuity_allowance_amount",9,2)->nullable();
            $table->float("profund_allowance_amount",9,2)->nullable();
            $table->float("extra_allowance_amount",9,2)->nullable();
            $table->float("dashain_allowance_amount",9,2)->nullable();
            $table->float("other_allowance_amount",9,2)->nullable();
            $table->tinyInteger("special_allow_2")->nullable();
            $table->tinyInteger("outstation_facility_allow")->nullable();
        });
    }
}
