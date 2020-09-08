<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemEduMast extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_edu_mast', function (Blueprint $table) {
            $table->increments("edu_id");
            $table->string("edu_description", 100);
	        $table->integer('created_by')->nullable();
	        $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists("system_edu_mast");
    }
}
