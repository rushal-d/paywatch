<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffCitDeductionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_cit_deductions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('staff_central_id');
            $table->unsignedInteger('branch_id');
            $table->unsignedInteger('fiscal_year_id');
            $table->unsignedBigInteger('month_id');
            $table->unsignedInteger('payroll_id')->nullable();
            $table->unsignedDecimal('cit_deduction_amount');
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('staff_central_id')->references('id')->on('staff_main_mast');
            $table->foreign('branch_id')->references('office_id')->on('system_office_mast');
            $table->foreign('fiscal_year_id')->references('id')->on('fiscal_year');
            $table->foreign('payroll_id')->references('id')->on('payroll_details');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('deleted_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staff_cit_deductions');
    }
}
