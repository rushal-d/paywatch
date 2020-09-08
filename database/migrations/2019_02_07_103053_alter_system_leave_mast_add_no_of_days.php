<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSystemLeaveMastAddNoOfDays extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('system_leave_mast', function (Blueprint $table) {
            $table->integer('no_of_days')->default(0)->after('max_days');
            $table->boolean('initial_setup')->default(0)->after('no_of_days');
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
            $table->dropColumn('no_of_days');
            $table->dropColumn('initial_setup');
        });
    }
}
