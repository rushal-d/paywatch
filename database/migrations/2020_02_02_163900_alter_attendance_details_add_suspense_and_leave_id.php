<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAttendanceDetailsAddSuspenseAndLeaveId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendance_details', function (Blueprint $table) {
            $table->integer('suspense')->after('status')->default(0);
            $table->unsignedInteger('leave_id')->after('suspense')->nullable();
            $table->foreign('leave_id')->references('leave_id')->on('system_leave_mast');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendance_details', function (Blueprint $table) {
            $table->dropForeign(['leave_id']);
            $table->dropColumn('leave_id');
            $table->dropColumn('suspense');
        });
    }
}
