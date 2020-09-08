<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffSalaryMastTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_salary_mast', function (Blueprint $table) {
            $table->bigIncrements('salary_id');
            $table->bigInteger("staff_central_id")->unsigned();
            $table->foreign('staff_central_id')->references('id')->on('staff_main_mast')->onDelete('cascade');
            $table->float("add_salary_amount")->nullable();
            $table->date("salary_effected_date")->nullable();
            $table->string("salary_effected_date_np", 15)->nullable();
            $table->string("salary_payment_status",1)->nullable();
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
        Schema::dropIfExists('staff_salary_mast');
    }
}
