<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSystemOfficeMastAddAlternetiveShiftEnableFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('system_office_mast', function (Blueprint $table) {
            $table->boolean('enable_alternative_shift')->default(0);
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
            $table->dropColumn('enable_alternative_shift');
        });
    }
}
