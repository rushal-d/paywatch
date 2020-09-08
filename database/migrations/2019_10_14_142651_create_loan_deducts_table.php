<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoanDeductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_deducts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('fiscal_year_id');
            $table->unsignedInteger('loan_type');
            $table->unsignedInteger('loan_id');

            $table->unsignedInteger('month_id');

            $table->unsignedDecimal('loan_deduct_amount', '8', '2');
            $table->text('remarks');


            $table->foreign('fiscal_year_id')->references('id')->on('fiscal_year');

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
        Schema::dropIfExists('loan_deducts');
    }
}
