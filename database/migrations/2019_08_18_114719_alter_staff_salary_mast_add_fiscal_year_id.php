<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterStaffSalaryMastAddFiscalYearId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staff_salary_mast', function (Blueprint $table) {
            $table->unsignedInteger('fiscal_year_id')->after('post_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('staff_salary_mast', function (Blueprint $table) {
            $table->dropColumn('fiscal_year_id');
        });
    }
}
