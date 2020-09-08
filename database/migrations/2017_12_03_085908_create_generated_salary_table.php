<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeneratedSalaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('generated_salary', function (Blueprint $table) {
            $table->bigIncrements('salary_id');
            $table->date("date_salary")->nullable();
            $table->bigInteger("staff_central_id")->unsigned();
            $table->foreign('staff_central_id')->references('id')->on('staff_main_mast')->onDelete('cascade');
            $table->string("staff_branch_id",7)->nullable();
            $table->string("namel",100)->nullable();
            $table->double("worked_hrs")->nullable();
            $table->integer("saved_leave")->nullable();
            $table->double("ot_hrs")->nullable();
            $table->double("calculated_salary")->nullable();
            $table->double("salary_amt")->nullable();
            $table->double("ot_amountl")->nullable();
            $table->double("dear_amt")->nullable();
            $table->decimal("risk_allow")->nullable();
            $table->double("other_allow")->nullable();
            $table->double("total_receivable")->nullable();
            $table->double("gratuti_dedu")->nullable();
            $table->double("profund_dedu")->nullable();
            $table->double("sundry_dedu")->nullable();
            $table->double("house_dedu")->nullable();
            $table->double("vehi_dedu")->nullable();
            $table->double("absent_dedu")->nullable();
            $table->double("net_payble")->nullable();
            $table->string("payment_moad",45)->nullable();
            $table->string("prement_moad",45)->nullable();
            $table->string("prepare_by",45)->nullable();
            $table->string("payment_stat",1)->nullable();
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
        Schema::dropIfExists('generated_salary');
    }
}
