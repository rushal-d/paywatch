<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemTdsMastTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_tds_mast', function (Blueprint $table) {
            $table->increments('tds_id');
            $table->string("tds_descri",45)->nullable();
            $table->string("fy_year",15)->nullable();
            $table->double("income_range")->nullable();
            $table->double("deduct_per")->nullable();
            $table->string("autho_id",7)->nullable();
            $table->string("tds_stat",1)->nullable();
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
        Schema::dropIfExists('system_tds_mast');
    }
}
