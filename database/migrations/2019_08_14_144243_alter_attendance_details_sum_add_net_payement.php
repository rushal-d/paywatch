<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAttendanceDetailsSumAddNetPayement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendance_details_sum', function (Blueprint $table) {
            $table->double('net_payment', 10, 2)->default(0);
            $table->integer('suspense_days')->default(0);
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
            $table->dropColumn('net_payment');
            $table->dropColumn('suspense_days');
        });
    }
}
