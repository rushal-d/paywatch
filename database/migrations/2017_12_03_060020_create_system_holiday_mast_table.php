<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemHolidayMastTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_holiday_mast', function (Blueprint $table) {
            $table->increments('holiday_id');
            $table->string("fy_year", 15)->nullable();
            $table->string("holiday_descri", 45)->nullable();
            $table->integer("holiday_days")->nullable();
            $table->string("autho_id", 7)->nullable();
            $table->string("holiday_stat", 1)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_holiday_mast');
    }
}
