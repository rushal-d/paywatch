<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSystemJobtypeMastAddSocialSecurityFundPer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('system_jobtype_mast', function (Blueprint $table) {
            $table->float('social_security_fund_per')->after('profund_contri_per')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('system_jobtype_mast', function (Blueprint $table) {
            $table->dropColumn('social_security_fund_per');
        });
    }
}
