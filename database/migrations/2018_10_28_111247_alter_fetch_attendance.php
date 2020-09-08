<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFetchAttendance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fetch_attendances', function (Blueprint $table) {
            $table->dropColumn('date_np');
            $table->dropColumn('date');
            $table->string('punchin_datetime_np');
            $table->dateTime('punchin_datetime');
            $table->string('punchout_datetime_np');
            $table->dateTime('punchout_datetime');
            $table->string('tiffinin_datetime_np')->nullable();
            $table->dateTime('tiffinin_datetime')->nullable();
            $table->string('tiffinout_datetime_np')->nullable();
            $table->dateTime('tiffinout_datetime')->nullable();
            $table->string('lunchin_datetime_np')->nullable();
            $table->dateTime('lunchin_datetime')->nullable();
            $table->string('lunchout_datetime_np')->nullable();
            $table->dateTime('lunchout_datetime')->nullable();
            $table->string('personalin_datetime_np')->nullable();
            $table->dateTime('personalin_datetime')->nullable();
            $table->string('personalout_datetime_np')->nullable();
            $table->dateTime('personalout_datetime')->nullable();
            $table->tinyInteger('status')->nullable();
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
            $table->string('date_np');
            $table->date('date');
            $table->dropColumn('punchin_datetime_np');
            $table->dropColumn('punchin_datetime');
            $table->dropColumn('punchout_datetime_np');
            $table->dropColumn('punchout_datetime');
            $table->dropColumn('tiffinin_datetime_np')->nullable();
            $table->dropColumn('tiffinin_datetime')->nullable();
            $table->dropColumn('tiffinout_datetime_np')->nullable();
            $table->dropColumn('tiffinout_datetime')->nullable();
            $table->dropColumn('lunchin_datetime_np')->nullable();
            $table->dropColumn('lunchin_datetime')->nullable();
            $table->dropColumn('lunchout_datetime_np')->nullable();
            $table->dropColumn('lunchout_datetime')->nullable();
            $table->dropColumn('personalin_datetime_np')->nullable();
            $table->dropColumn('personalin_datetime')->nullable();
            $table->dropColumn('personalout_datetime_np')->nullable();
            $table->dropColumn('personalout_datetime')->nullable();
            $table->dropColumn('status')->nullable();
        });
    }
}
