<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAttendanceDetailsAddColums extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendance_details_sum', function (Blueprint $table) {
            $table->double('total_work_hour',6,2);
            $table->double('total_ot_hour',6,2);
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
            $table->dropColumn('total_work_hour')->after('date_np');
            $table->dropColumn('total_ot_hour')->after('total_work_hour');
        });
    }
}
