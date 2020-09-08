<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehicleLoanTransactionLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_loan_transaction_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vehical_id')->unsigned();
            $table->bigInteger("staff_central_id")->unsigned();
            $table->foreign('staff_central_id')->references('id')->on('staff_main_mast')->onDelete('cascade');
            $table->date("trans_date")->nullable();
            $table->double("paid_installment_amt")->nullable();
            $table->double("remaining_amt")->nullable();
            $table->string("detail_note", 100)->nullable();
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
        Schema::dropIfExists('vehicle_loan_transaction_logs');
    }
}
