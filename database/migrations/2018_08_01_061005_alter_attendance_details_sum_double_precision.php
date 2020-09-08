<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAttendanceDetailsSumDoublePrecision extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendance_details_sum', function (Blueprint $table) {
            $table->dropColumn('total_work_hour');
            $table->dropColumn('total_ot_hour');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendance_details_sum', function (Blueprint $table) {
            //
        });
    }
}
