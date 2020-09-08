<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPayrollDetailsAddDeletedAtAndDeletedBy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payroll_details', function (Blueprint $table) {
            $table->unsignedInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users');
            $table->softDeletes();
        });
        Schema::table('leave_balance', function (Blueprint $table) {
            $table->unsignedInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users');
            $table->unsignedInteger('payroll_id')->nullable();
            $table->foreign('payroll_id')->references('id')->on('payroll_details');
        });

        Schema::table('sundry_transaction_logs', function (Blueprint $table) {
            $table->unsignedInteger('payroll_id')->nullable();
            $table->foreign('payroll_id')->references('id')->on('payroll_details');
        });

        Schema::table('calender_holiday', function (Blueprint $table) {
            $table->unsignedInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');

            $table->unsignedInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');

            $table->unsignedInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users');

            $table->unsignedInteger('payroll_id')->nullable();
            $table->foreign('payroll_id')->references('id')->on('payroll_details');
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
            $table->dropSoftDeletes();
            $table->dropForeign(['deleted_by']);
            $table->dropColumn('deleted_by');
        });
        Schema::table('leave_balance', function (Blueprint $table) {
            $table->dropForeign(['deleted_by']);
            $table->dropColumn('deleted_by');
            $table->dropForeign(['payroll_id']);
            $table->dropColumn('payroll_id');
        });

        Schema::table('sundry_transaction_logs', function (Blueprint $table) {
            $table->dropForeign(['payroll_id']);
            $table->dropColumn('payroll_id');
        });

        Schema::table('calender_holiday', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');

            $table->dropForeign(['updated_by']);
            $table->dropColumn('updated_by');

            $table->dropForeign(['deleted_by']);
            $table->dropColumn('deleted_by');

            $table->dropForeign(['payroll_id']);
            $table->dropColumn('payroll_id');
        });
    }
}
