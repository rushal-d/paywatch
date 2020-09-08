<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDashainSetupAddIncentiveAmount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dashain_tihar_setups', function (Blueprint $table) {
            $table->double('incentive_amount', 9, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dashian_tihar_setups', function (Blueprint $table) {
            //
        });
    }
}
