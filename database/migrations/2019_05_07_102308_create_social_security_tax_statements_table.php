<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocialSecurityTaxStatementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_security_tax_statements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('payroll_id')->unsigned();
            $table->foreign('payroll_id')
                ->references('id')->on('payroll_details')
                ->onDelete('cascade');
            $table->integer('staff_central_id')->unsigned();
            $table->integer('branch_id')->unsigned();
            $table->integer('post_id')->unsigned();
            $table->double('tax_amount', 8, 2)->unsigned();
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
        Schema::dropIfExists('social_security_tax_statements');
    }
}
