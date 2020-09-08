<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemPostMast extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_post_mast', function (Blueprint $table) {
	        $table->increments('post_id');
	        $table->string("post_title", 45)->nullable();
	        $table->double("basic_salary")->nullable();
	        $table->date("effect_date")->nullable();
	        $table->string("effect_date_np", 15)->nullable();
	        $table->float("grade_amount",10,2)->nullable();
	        $table->integer("grade_id")->nullable();
	        $table->integer("autho_id")->nullable();
	        $table->string("status_id", 1)->nullable();
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
        Schema::dropIfExists('system_post_mast');
    }
}
