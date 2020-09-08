<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemLeaveMastUseabilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('system_leave_mast', function (Blueprint $table) {
            $table->dropColumn('useability_count');
            $table->dropColumn('useability_count_unit'); // days in fiscal year, time(s),days in month, times in month
        });
        Schema::create('system_leave_mast_useabilities', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('system_leave_id');
            $table->foreign('system_leave_id')->references('leave_id')->on('system_leave_mast');
            $table->float('useability_count');
            $table->integer('useability_count_unit'); // days in fiscal year, time(s),days in month, times in month
            $table->timestamps();
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
            $table->float('useability_count')->nullable();
            $table->integer('useability_count_unit')->nullable(); // days in fiscal year, time(s),days in month, times in month
        });
        Schema::dropIfExists('system_leave_mast_useabilities');
    }
}
