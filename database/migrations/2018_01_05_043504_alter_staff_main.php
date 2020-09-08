<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterStaffMain extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('staff_main_mast', function(Blueprint $table){
            $table->integer("dear_allow")->nullable();
            $table->integer("extra_allow")->nullable();
            $table->integer("special_allow")->nullable();
            $table->integer("gratuity_allow")->nullable();
            $table->integer("risk_allow")->nullable();
            $table->integer("dashain_allow")->nullable();
            $table->integer("other_allow")->nullable();
            $table->string("staff_citizen_no")->nullable();
            $table->date("staff_dob")->nullable();
            $table->string("staff_citizen_issue_office")->nullable();
            $table->string("staff_citizen_issue_date_np")->nullable();
            $table->integer("bank_id")->unsigned()->nullable();
            $table->foreign('bank_id')->references('id')->on('bank_mast')->onDelete('cascade');
            $table->string("acc_no")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('staff_main_mast', function(Blueprint $table){
            $table->dropColumn('dear_allow');
            $table->dropColumn('extra_allow');
            $table->dropColumn('special_allow');
            $table->dropColumn('gratuity_allow');
            $table->dropColumn('dashain_allow');
            $table->dropColumn('staff_citizen_no');
            $table->dropColumn('staff_citizen_issue_office');
            $table->dropColumn('staff_citizen_issue_date_np');
	        $table->dropForeign(['bank_id']);
            $table->dropColumn('bank_id');
            $table->dropColumn('acc_no');
        });
    }
}
