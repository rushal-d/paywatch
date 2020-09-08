<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffInsurancePremiumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_insurance_premium', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('staff_central_id');
            $table->foreign('staff_central_id')->references('id')->on('staff_main_mast');
            $table->unsignedInteger('fiscal_year_id');
            $table->foreign('fiscal_year_id')->references('id')->on('fiscal_year');
            $table->unsignedInteger('branch_id');
            $table->foreign('branch_id')->references('office_id')->on('system_office_mast');
            $table->double('premium_amount', 10, 2);
            $table->unsignedInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->unsignedInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users');
            $table->softDeletes();
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
        Schema::dropIfExists('staff_insurance_premium');
    }
}
