<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColsToPayrollDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payroll_details', function (Blueprint $table) {
            $table->date("from_date")->nullable();
            $table->date("to_date")->nullable();
            $table->string("from_date_np")->nullable();
            $table->string("to_date_np")->nullable();
            $table->integer("total_days")->nullable();
            $table->integer("total_public_holidays")->nullable();
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
            $table->dropColumn("from_date");
            $table->dropColumn("to_date");
            $table->dropColumn("from_date_np");
            $table->dropColumn("to_date_np");
            $table->dropColumn("total_days");
            $table->dropColumn("total_public_holidays");
        });
    }
}
