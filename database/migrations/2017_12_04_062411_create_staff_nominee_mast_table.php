<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffNomineeMastTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_nominee_mast', function (Blueprint $table) {
            $table->bigIncrements('nominee_id');
            $table->bigInteger("staff_central_id")->unsigned();
            $table->foreign('staff_central_id')->references('id')->on('staff_main_mast')->onDelete('cascade');
            $table->date("appli_date")->nullable();
            $table->string("appli_date_np", 15)->nullable();
           $table->string("relation", 45)->nullable();
            $table->string("nominee_name", 100)->nullable();
            $table->string("dob")->nullable();
            $table->string("citizen_no",45)->nullable();
            $table->string("issue_office",45)->nullable();
            $table->date("issue_date")->nullable();
            $table->string("issue_date_np", 15)->nullable();
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
        Schema::dropIfExists('staff_nominee_mast');
    }
}
