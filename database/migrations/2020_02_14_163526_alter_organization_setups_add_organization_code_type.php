<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrganizationSetupsAddOrganizationCodeType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organization_setups', function (Blueprint $table) {
            $table->string('organization_code')->nullable();
            $table->integer('organization_type')->default(1);
            $table->integer('organization_structure')->default(1);
            $table->integer('absent_weekend_on_cons_absent')->default(1);
            $table->integer('absent_publicholiday_on_cons_absent')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organization_setups', function (Blueprint $table) {
            $table->dropColumn('organization_code');
            $table->dropColumn('organization_type');
            $table->dropColumn('organization_structure');
            $table->dropColumn('absent_weekend_on_cons_absent');
            $table->dropColumn('absent_publicholiday_on_cons_absent');
        });
    }
}
