<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffTransferMastTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_transefer_mast', function (Blueprint $table) {
            $table->bigIncrements('transfer_id');
            $table->bigInteger("staff_central_id")->unsigned();
            $table->foreign('staff_central_id')->references('id')->on('staff_main_mast')->onDelete('cascade');
            $table->date("from_date")->nullable();
            $table->string("from_date_np", 15)->nullable();
            $table->date("to_date")->nullable();
            $table->string("to_date_np", 15)->nullable();
            $table->string("autho_id", 75)->nullable();
            $table->integer("office_id")->unsigned();
            $table->foreign('office_id')->references('office_id')->on('system_office_mast')->onDelete('cascade');
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
        Schema::dropIfExists('staff_transefer_mast');
    }
}
