<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemAllwanceMastTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_allwance_mast', function (Blueprint $table) {
            $table->increments('allow_id');
            $table->string("allow_title", 45)->nullable();
            $table->double("allow_amt")->nullable();
            $table->date("effect_date")->nullable();
            $table->string("effect_date_np", 15)->nullable();
            $table->integer("autho_id")->nullable();
            $table->string("status_id", 1)->nullable();
            $table->string("allow_code", 3)->nullable();
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
        Schema::dropIfExists('system_allwance_mast');
    }
}
