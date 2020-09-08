<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterStaffMainMastAddStaffCentralId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staff_main_mast', function (Blueprint $table) {
            $table->unsignedInteger('staff_central_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('staff_main_mast', function (Blueprint $table) {
            $table->dropColumn('staff_central_id')->nullable();
        });
    }
}
