<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreColsStaffMasterTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staff_main_mast', function (Blueprint $table) {
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
	        $table->tinyInteger("section")->nullable();
	        $table->tinyInteger("department")->nullable();
	        $table->string("work_start_date_np", 20)->nullable();
	        $table->date("work_start_date")->nullable();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('staff_main_mast', function (Blueprint $table) {
            $table->dropColumn('outstation_facility_amount');
            $table->dropColumn('special_allowance_amount');
            $table->dropColumn('special_allowance_2_amount');
            $table->dropColumn('risk_allowance_amount');
            $table->dropColumn('dearness_allowance_amount');
            $table->dropColumn('gratuity_allowance_amount');
            $table->dropColumn('profund_allowance_amount');
            $table->dropColumn('extra_allowance_amount');
	        $table->dropColumn('special_allow_2');
            $table->dropColumn('outstation_facility_allow');
            $table->dropColumn('section');
            $table->dropColumn('department');
            $table->dropColumn('work_start_date_np');
            $table->dropColumn('work_start_date');

        });
    }
}
