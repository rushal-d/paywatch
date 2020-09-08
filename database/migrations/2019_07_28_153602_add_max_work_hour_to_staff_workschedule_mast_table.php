<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMaxWorkHourToStaffWorkscheduleMastTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staff_workschedule_mast', function (Blueprint $table) {
            $table->integer('max_work_hour')->after('work_hour')->default(8);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('staff_workschedule_mast', function (Blueprint $table) {
            $table->dropColumn('max_work_hour');
        });
    }
}
