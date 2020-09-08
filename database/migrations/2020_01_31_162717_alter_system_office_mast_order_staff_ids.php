<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSystemOfficeMastOrderStaffIds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('system_office_mast', function (Blueprint $table) {
            $table->text('order_staff_ids')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('system_office_mast', function (Blueprint $table) {
            $table->dropColumn('order_staff_ids');
        });
    }
}
