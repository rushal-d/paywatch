<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemTdsdetailsMast extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::dropIfExists('system_tds_mast');
        Schema::create('system_tdsdetails_mast', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('slab')->nullable;
            $table->integer('fy')->nullable;
            $table->double('amount')->nullable;
            $table->double('percent')->nullable;
            $table->integer('status')->nullable;
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
        Schema::dropIfExists('system_tdsdetails_mast');
    }
}
