<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayrollConfirmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payroll_confirms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('payroll_id')->unsigned();
            $table->foreign('payroll_id')
                ->references('id')->on('payroll_details')
                ->onDelete('cascade');
            $table->integer('staff_central_id');
            $table->integer('min_work_hour');
            $table->string('tax_code')->nullable();
            $table->integer('present_days');
            $table->integer('absent_days')->nullable();
            $table->integer('redeem_home_leave')->nullable();
            $table->integer('redeem_sick_leave')->nullable();
            $table->integer('salary_hour_payable');
            $table->integer('ot_hour_payable')->nullable();
            $table->double('basic_salary', 9, 2);
            $table->double('dearness_allowance', 9, 2)->nullable();
            $table->double('special_allowance', 9, 2)->nullable();
            $table->double('extra_allowance', 9, 2)->nullable();
            $table->double('pro_fund', 9, 2)->nullable();
            $table->double('pro_fund_contribution', 9, 2)->nullable();
            $table->double('home_sick_redeem_amount', 9, 2)->nullable();
            $table->double('ot_amount', 9, 2)->nullable();
            $table->double('outstation_facility_amount', 9, 2)->nullable();
            $table->double('gross_payable', 9, 2);
            $table->double('loan_payment', 9, 2)->nullable();
            $table->double('sundry_dr', 9, 2)->nullable();
            $table->double('sundry_cr', 9, 2)->nullable();
            $table->double('tax', 9, 2);
            $table->double('net_payable', 9, 2);
            $table->string('remarks', 9, 2)->nullable();
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
        Schema::dropIfExists('payroll_confirms');
    }
}
