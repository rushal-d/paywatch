<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDashainTiharSetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dashain_tihar_setups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('min_special_incentive_months');
            $table->float('extra_facility_dashain_tihar_rate');
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
        Schema::dropIfExists('dashain_tihar_setups');
    }
}
