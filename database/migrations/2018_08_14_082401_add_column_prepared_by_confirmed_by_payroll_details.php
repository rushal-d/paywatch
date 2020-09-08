<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnPreparedByConfirmedByPayrollDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payroll_details', function (Blueprint $table) {
            $table->integer('prepared_by')->nullable();
            $table->integer('confirmed_by')->nullable();
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
            $table->dropColumn('prepared_by');
            $table->dropColumn('confirmed_by');
        });
    }
}
