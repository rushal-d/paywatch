<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterStaffMainMastOrganizationSetupOtFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staff_main_mast', function (Blueprint $table) {
            $table->boolean('is_overtime_payable')->default(0);
        });
        Schema::table('organization_setups', function (Blueprint $table) {
            $table->float('max_overtime_hour')->nullable();
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
            $table->dropColumn('is_overtime_payable');
        });
        Schema::table('organization_setups', function (Blueprint $table) {
            $table->dropColumn('max_overtime_hour');
        });
    }
}
