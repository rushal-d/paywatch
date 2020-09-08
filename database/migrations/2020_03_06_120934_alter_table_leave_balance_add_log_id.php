<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableLeaveBalanceAddLogId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leave_balance', function (Blueprint $table){
           $table->unsignedInteger('log_id')->nullable();
           $table->foreign('log_id')->references('id')->on('earnable_balance_month_logs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leave_balance', function (Blueprint $table){
           $table->dropForeign(['log_id']);
           $table->dropColumn('log_id');
        });
    }
}
