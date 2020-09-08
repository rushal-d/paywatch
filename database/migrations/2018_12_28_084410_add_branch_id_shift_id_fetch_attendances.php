<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBranchIdShiftIdFetchAttendances extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fetch_attendances', function (Blueprint $table) {
            $table->unsignedInteger('branch_id');
            $table->foreign('branch_id')->references('office_id')->on('system_office_mast');
            $table->unsignedInteger('shift_id');
            $table->foreign('shift_id')->references('id')->on('shifts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fetch_attendances', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropForeign(['shift_id']);

            $table->dropColumn('branch_id');
            $table->dropColumn('shift_id');
        });
    }
}
