<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransTiharPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trans_tihar_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('payroll_id');
            $table->integer('fiscal_year')->unsigned();
            $table->foreign('fiscal_year')->references('id')->on('fiscal_year')->onDelete('cascade');
            $table->integer('staff_central_id')->unsigned();
            $table->integer('branch_id')->unsigned();
            $table->foreign('branch_id')->references('office_id')->on('system_office_mast')->onDelete('cascade');
            $table->date('payment_date');
            $table->double('advance_amount_taken')->nullable();
            $table->integer('worked_months')->nullable();
            $table->double('tihar_bonus_before_tax', 9, 2);
            $table->double('tax_amount', 9, 2);
            $table->double('tihar_bonus_after_tax', 9, 2);
            $table->double('net_payable', 9, 2);
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
        Schema::dropIfExists('trans_tihar_payments');
    }
}
