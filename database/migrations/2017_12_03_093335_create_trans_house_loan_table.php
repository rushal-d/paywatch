<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransHouseLoanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trans_house_loan', function (Blueprint $table) {
            $table->bigIncrements('house_id');
            $table->bigInteger("staff_central_id")->unsigned();
            $table->foreign('staff_central_id')->references('id')->on('staff_main_mast')->onDelete('cascade');
            $table->date("trans_date")->nullable();
            $table->double("stallment_amt")->nullable();
            $table->double("deduct_amt")->nullable();
            $table->double("paid_amt")->nullable();
            $table->double("balance_amt")->nullable();
            $table->string("autho_id",7)->nullable();
            $table->string("account_status",1)->nullable();
            $table->string("detail_note",100)->nullable();
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
        Schema::dropIfExists('trans_house_loan');
    }
}
