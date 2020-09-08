<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayrollCalculationDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payroll_calculation_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('payroll_id');
            $table->integer('staff_central_id');
            $table->integer('redeem_home_leave')->nullable();
            $table->integer('redeem_sick_leave')->nullable();
            $table->integer('check_house_loan')->nullable();
            $table->integer('check_vehicle_loan')->nullable();
            $table->integer('check_sundry_loan')->nullable();
            $table->double('misc_amount', 9, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void->nullable()
     */
    public function down()
    {
        Schema::dropIfExists('payroll_calculation_datas');
    }
}
