<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHouseLoanDiffIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('house_loan_diff_incomes', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('house_loan_id');
            $table->unsignedInteger('fiscal_year_id');
            $table->double("diff_income")->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('house_loan_id')->references('house_id')->on('trans_house_loan');
            $table->foreign('fiscal_year_id')->references('id')->on('fiscal_year');
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
        Schema::dropIfExists('house_loan_diff_incomes');
    }
}
