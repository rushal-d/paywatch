<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEarnableBalanceMonthLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('earnable_balance_month_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('fiscal_year_id');
            $table->foreign('fiscal_year_id')->references('id')->on('fiscal_year');
            $table->unsignedInteger('month_id')->nullable();
            $table->unsignedInteger('leave_earnable_period_type')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('earnable_balance_month_logs');
    }
}
