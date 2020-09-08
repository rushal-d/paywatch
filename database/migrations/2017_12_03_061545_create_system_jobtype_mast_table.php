<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemJobtypeMastTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create('system_jobtype_mast', function (Blueprint $table) {
		    $table->increments('jobtype_id');
		    $table->string("jobtype_name", 45)->nullable();
		    $table->date("effect_date")->nullable();
		    $table->string("effect_date_np", 15)->nullable();
		    $table->double("gratuity")->nullable();
		    $table->double("profund_per")->nullable();
		    $table->double("profund_contri_per")->nullable();
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
        Schema::dropIfExists('system_jobtype_mast');
    }
}
