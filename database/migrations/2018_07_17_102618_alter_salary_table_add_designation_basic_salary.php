<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSalaryTableAddDesignationBasicSalary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staff_salary_mast', function (Blueprint $table) {
            $table->integer('post_id')->after('staff_central_id');
            $table->float('basic_salary',9,2)->after('post_id');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
            $table->dropColumn('post_id');
            $table->dropColumn('basic_salary');
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
        });
    }
}
