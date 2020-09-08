<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBranchIdColumnToEarnableBalanceMonthLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('earnable_balance_month_logs', function (Blueprint $table) {
            $table->unsignedInteger('branch_id')->nullable()->after('month_id');
            $table->foreign('branch_id')->references('office_id')->on("system_office_mast");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('earnable_balance_month_logs', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn('branch_id');
        });
    }
}
