<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransCashStatementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trans_cash_statements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('payroll_id')->unsigned();
            $table->foreign('payroll_id')
                ->references('id')->on('payroll_details')
                ->onDelete('cascade');
            $table->integer('staff_central_id');
            $table->integer('branch_id');
            $table->double('total_payment', 9, 2);
            $table->text('remarks');
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
        Schema::dropIfExists('trans_cash_statements');
    }
}
