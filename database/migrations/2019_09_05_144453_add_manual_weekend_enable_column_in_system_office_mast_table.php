<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddManualWeekendEnableColumnInSystemOfficeMastTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('system_office_mast', function (Blueprint $table) {
            $table->boolean('manual_weekend_enable')->default(0)->after('location');
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
            $table->dropColumn('manual_weekend_enable');
        });
    }
}
