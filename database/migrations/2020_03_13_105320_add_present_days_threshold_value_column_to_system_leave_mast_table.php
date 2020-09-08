<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPresentDaysThresholdValueColumnToSystemLeaveMastTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('system_leave_mast', function (Blueprint $table) {
            $table->unsignedInteger('threshold_for_present_days')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('system_leave_mast', function (Blueprint $table) {
            $table->dropColumn('threshold_for_present_days');
        });
    }
}
