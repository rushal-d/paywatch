<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPayrollConfirmsAddGradeAmount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payroll_confirms', function (Blueprint $table) {
            $table->double('grade_amount',10,2)->default(0);
            $table->string('remarks',255)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payroll_confirms', function (Blueprint $table) {
            $table->dropColumn('grade_amount',10,2)->default(0);
        });
    }
}
