<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSystemDistMaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("system_dist_mast", function(Blueprint $table){
            $table->integer("id")->unsigned();
            $table->primary('id');
            $table->integer("district_id")->unsigned()->nullable();;
            $table->string("district_name_np");
            $table->string("district_name");
            $table->string("mun_vdc");
            $table->string("type");
            $table->integer("province");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("system_dist_mast");
    }
}