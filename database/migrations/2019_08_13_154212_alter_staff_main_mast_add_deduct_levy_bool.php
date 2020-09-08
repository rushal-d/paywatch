<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterStaffMainMastAddDeductLevyBool extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staff_main_mast', function (Blueprint $table) {
            $table->boolean('deduct_levy')->default(1);
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
            $table->dropColumn('deduct_levy');
        });
    }
}
