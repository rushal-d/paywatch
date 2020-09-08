<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemOfficeMastTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_office_mast', function (Blueprint $table) {
            $table->increments('office_id');
            $table->string("office_name", 100)->nullable();
            $table->string("office_location", 30)->nullable();
            $table->date("estd_date")->nullable();
            $table->string("estd_date_np", 15)->nullable();
            $table->string("location")->nullable();
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
        Schema::dropIfExists('system_office_mast');
    }
}
