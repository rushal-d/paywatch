<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterStaffMainMastAddSocialSecurityFundAccNo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staff_main_mast', function (Blueprint $table) {
            $table->string('social_security_fund_acc_no')->nullable();
            $table->date('temporary_con_date')->nullable();
            $table->string('temporary_con_date_np')->nullable();
            $table->date('permanent_date')->nullable();
            $table->string('permanent_date_np')->nullable();
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
            $table->dropColumn('social_security_fund_acc_no');
            $table->dropColumn('temporary_con_date');
            $table->dropColumn('temporary_con_date_np');
            $table->dropColumn('permanent_date');
            $table->dropColumn('permanent_date_np');
        });
    }
}
