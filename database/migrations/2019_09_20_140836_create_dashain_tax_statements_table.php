<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDashainTaxStatementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dashain_tax_statements', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('payroll_id');
            $table->double('expenses_social_security_tax',9,2)->default(0);
            $table->double('expenses_income_tax',9,2)->default(0);
            $table->double('bonus_social_security_tax',9,2)->default(0);
            $table->double('bonus_income_tax',9,2)->default(0);
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
        Schema::dropIfExists('dashain_tax_statements');
    }
}
