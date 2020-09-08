<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterVehicleLoanRemoveStallAmtAddLoanAmtNoInstall extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('trans_vehical_loan');
        Schema::create('trans_vehical_loan', function (Blueprint $table) {
            $table->bigIncrements('vehical_id');
            $table->bigInteger("staff_central_id")->unsigned();
            $table->foreign('staff_central_id')->references('id')->on('staff_main_mast')->onDelete('cascade');
            $table->date("trans_date")->nullable();
            $table->double("loan_amount")->nullable();
            $table->double("no_installment")->nullable();
            $table->float("installment_amount")->nullable();
            $table->double("deduct_amt")->nullable();
            $table->double("paid_amt")->nullable();
            $table->double("balance_amt")->nullable();
            $table->string("autho_id", 7)->nullable();
            $table->string("account_status", 1)->default(0);
            $table->string("detail_note", 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('trans_house_loan', function (Blueprint $table) {
            $table->integer('account_status')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trans_vehical_loan');
    }
}
