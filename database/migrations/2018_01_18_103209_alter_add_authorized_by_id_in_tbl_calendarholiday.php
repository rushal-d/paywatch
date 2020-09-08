<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddAuthorizedByIdInTblCalendarholiday extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('calender_holiday', function (Blueprint $table) {
            $table->integer('authorized_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('calender_holiday', function (Blueprint $table) {
	        $table->dropColumn('authorized_by');
        });
    }
}
