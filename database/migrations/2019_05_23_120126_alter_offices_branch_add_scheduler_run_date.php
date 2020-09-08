<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOfficesBranchAddSchedulerRunDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('system_office_mast', function (Blueprint $table) {
            $table->date('paywatch_implementation_date')->nullable();
            $table->text('paywatch_implementation_date_np')->nullable();
            $table->date('schedule_run_date')->nullable();
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
            $table->dropColumn('paywatch_implementation_date');
            $table->dropColumn('paywatch_implementation_date_np');
            $table->dropColumn('schedule_run_date');
        });
    }
}
