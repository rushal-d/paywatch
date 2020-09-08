<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterStaffSalaryMastAddTotalGradeAmount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staff_salary_mast', function (Blueprint $table) {
            $table->float('total_grade_amount', 10, 2)->default(0)->after('add_salary_amount');
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
            $table->dropColumn('total_grade_amount');
        });
    }
}
