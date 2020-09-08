<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFiscalYearAttendanceSumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fiscal_year_attendance_sums', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fiscal_year')->unsigned();
            $table->foreign('fiscal_year')->references('id')->on('fiscal_year')->onDelete('cascade');
            $table->integer('staff_central_id')->unsigned();
            $table->integer('branch_id')->unsigned();
            $table->foreign('branch_id')->references('office_id')->on('system_office_mast')->onDelete('cascade');
            $table->integer('total_attendance')->default(0);
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
        Schema::dropIfExists('fiscal_year_attendance_sums');
    }
}
