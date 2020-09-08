<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterStaffSalaryMastAddGradeThisFiscalYear extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staff_salary_mast', function (Blueprint $table) {
            $table->double('add_grade_this_fiscal_year', 9, 2)->after('total_grade_amount')->default(0);
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
            $table->dropColumn('add_grade_this_fiscal_year');
        });
    }
}
