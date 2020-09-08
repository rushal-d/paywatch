<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHasBonusPayrollDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payroll_details', function (Blueprint $table) {
            $table->integer('has_bonus')->nullable();;
            $table->string('payroll_file')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payroll_details', function (Blueprint $table) {
            $table->dropColumn('has_bonus');
        });
    }
}
