<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTablesAddCreatedUpdatedDeletedColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dashain_tihar_setups', function (Blueprint $table) {
            $table->unsignedInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
        });

        Schema::table('staff_main_mast', function (Blueprint $table) {
            $table->unsignedInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->unsignedInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users');
        });

        Schema::table('app_versions', function (Blueprint $table) {
            $table->unsignedInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->unsignedInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users');
        });
        Schema::table('castes', function (Blueprint $table) {
            $table->unsignedInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->unsignedInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users');
        });

        Schema::table('religions', function (Blueprint $table) {
            $table->unsignedInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->unsignedInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users');
        });

        Schema::table('bank_mast', function (Blueprint $table) {
            $table->unsignedInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->unsignedInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users');
        });

        Schema::table('departments', function (Blueprint $table) {
            $table->softDeletes();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users');
        });

        Schema::table('sundry_balances', function (Blueprint $table) {
            $table->softDeletes();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users');
        });

        Schema::table('employee_statuses', function (Blueprint $table) {
            $table->softDeletes();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users');
        });

        Schema::table('alternative_day_shifts', function (Blueprint $table) {
            $table->unsignedInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->unsignedInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users');
            $table->softDeletes();
        });

        Schema::table('loan_deducts', function (Blueprint $table) {
            $table->unsignedInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->unsignedInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users');
        });

        Schema::table('shifts', function (Blueprint $table) {
            $table->unsignedInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->unsignedInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users');
            $table->softDeletes();
        });

        Schema::table('fiscal_year', function (Blueprint $table) {
            $table->unsignedInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->unsignedInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users');
            $table->softDeletes();
        });

        Schema::table('staff_shift_histories', function (Blueprint $table) {
            $table->unsignedInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->unsignedInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users');
            $table->softDeletes();
        });

        Schema::table('staff_file', function (Blueprint $table) {
            $table->unsignedInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->unsignedInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users');
            $table->softDeletes();
        });

        Schema::table('staff_nominee_mast', function (Blueprint $table) {
            $table->unsignedInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->unsignedInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users');
        });

        Schema::table('staff_payment_mast', function (Blueprint $table) {
            $table->unsignedInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->unsignedInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users');
        });

        Schema::table('staff_salary_mast', function (Blueprint $table) {
            $table->unsignedInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users');
        });

        Schema::table('system_tdsdetails_mast', function (Blueprint $table) {
            $table->unsignedInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users');
        });

        Schema::table('system_edu_mast', function (Blueprint $table) {
            $table->unsignedInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users');
        });
        Schema::table('public_holidays', function (Blueprint $table) {
            $table->unsignedInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users');
        });

        Schema::table('staff_workschedule_mast', function (Blueprint $table) {
            $table->unsignedInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->unsignedInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users');
        });
        Schema::table('system_jobtype_mast', function (Blueprint $table) {
            $table->unsignedInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->unsignedInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users');
        });

        Schema::table('system_leave_mast', function (Blueprint $table) {
            $table->unsignedInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->unsignedInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users');
        });

        Schema::table('sundry_types', function (Blueprint $table) {
            $table->unsignedInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->unsignedInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users');
            $table->softDeletes();
        });

        Schema::table('system_office_mast', function (Blueprint $table) {
            $table->unsignedInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->unsignedInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users');
        });

        Schema::table('provinces', function (Blueprint $table) {
            $table->unsignedInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->unsignedInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users');
        });

        Schema::table('organization_setups', function (Blueprint $table) {
            $table->unsignedInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
        });

        Schema::table('system_post_mast', function (Blueprint $table) {
            $table->dropColumn('autho_id');
            $table->unsignedInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->unsignedInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users');
        });

        Schema::table('system_allwance_mast', function (Blueprint $table) {
            $table->dropColumn('autho_id');
            $table->unsignedInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->unsignedInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users');
        });
        Schema::dropIfExists('generated_salary');
        Schema::dropIfExists('trans_attendance');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dashain_tihar_setups', function (Blueprint $table) {
            $table->dropForeign(['updated_by']);
            $table->dropColumn('updated_by');
        });
        Schema::table('staff_main_mast', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');

            $table->dropForeign(['updated_by']);
            $table->dropColumn('updated_by');

            $table->dropForeign(['deleted_by']);
            $table->dropColumn('deleted_by');
        });

        Schema::table('app_versions', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');

            $table->dropForeign(['updated_by']);
            $table->dropColumn('updated_by');

            $table->dropForeign(['deleted_by']);
            $table->dropColumn('deleted_by');
        });

        Schema::table('castes', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');

            $table->dropForeign(['updated_by']);
            $table->dropColumn('updated_by');

            $table->dropForeign(['deleted_by']);
            $table->dropColumn('deleted_by');
        });
        Schema::table('religions', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');

            $table->dropForeign(['updated_by']);
            $table->dropColumn('updated_by');

            $table->dropForeign(['deleted_by']);
            $table->dropColumn('deleted_by');
        });
        Schema::table('bank_mast', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');

            $table->dropForeign(['updated_by']);
            $table->dropColumn('updated_by');

            $table->dropForeign(['deleted_by']);
            $table->dropColumn('deleted_by');
        });
        Schema::table('departments', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropForeign(['deleted_by']);
            $table->dropColumn('deleted_by');
        });
        Schema::table('sundry_balances', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropForeign(['deleted_by']);
            $table->dropColumn('deleted_by');
        });
        Schema::table('employee_statuses', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropForeign(['deleted_by']);
            $table->dropColumn('deleted_by');
        });

        Schema::table('alternative_day_shifts', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropForeign(['updated_by']);
            $table->dropColumn('updated_by');

            $table->dropForeign(['deleted_by']);
            $table->dropColumn('deleted_by');
        });

        Schema::table('loan_deducts', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');

            $table->dropForeign(['updated_by']);
            $table->dropColumn('updated_by');

            $table->dropForeign(['deleted_by']);
            $table->dropColumn('deleted_by');
        });

        Schema::table('shifts', function (Blueprint $table) {

            $table->dropSoftDeletes();

            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');

            $table->dropForeign(['updated_by']);
            $table->dropColumn('updated_by');

            $table->dropForeign(['deleted_by']);
            $table->dropColumn('deleted_by');
        });

        Schema::table('fiscal_year', function (Blueprint $table) {

            $table->dropSoftDeletes();

            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');

            $table->dropForeign(['updated_by']);
            $table->dropColumn('updated_by');

            $table->dropForeign(['deleted_by']);
            $table->dropColumn('deleted_by');
        });

        Schema::table('staff_shift_histories', function (Blueprint $table) {

            $table->dropSoftDeletes();

            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');

            $table->dropForeign(['updated_by']);
            $table->dropColumn('updated_by');

            $table->dropForeign(['deleted_by']);
            $table->dropColumn('deleted_by');
        });

        Schema::table('staff_file', function (Blueprint $table) {

            $table->dropSoftDeletes();

            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');

            $table->dropForeign(['updated_by']);
            $table->dropColumn('updated_by');

            $table->dropForeign(['deleted_by']);
            $table->dropColumn('deleted_by');
        });

        Schema::table('staff_nominee_mast', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');

            $table->dropForeign(['updated_by']);
            $table->dropColumn('updated_by');

            $table->dropForeign(['deleted_by']);
            $table->dropColumn('deleted_by');
        });
        Schema::table('staff_payment_mast', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');

            $table->dropForeign(['updated_by']);
            $table->dropColumn('updated_by');

            $table->dropForeign(['deleted_by']);
            $table->dropColumn('deleted_by');
        });

        Schema::table('staff_salary_mast', function (Blueprint $table) {
            $table->dropForeign(['deleted_by']);
            $table->dropColumn('deleted_by');
        });

        Schema::table('system_tdsdetails_mast', function (Blueprint $table) {
            $table->dropForeign(['deleted_by']);
            $table->dropColumn('deleted_by');
        });

        Schema::table('system_edu_mast', function (Blueprint $table) {
            $table->dropForeign(['deleted_by']);
            $table->dropColumn('deleted_by');
        });

        Schema::table('public_holidays', function (Blueprint $table) {
            $table->dropForeign(['deleted_by']);
            $table->dropColumn('deleted_by');
        });

        Schema::table('staff_workschedule_mast', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');

            $table->dropForeign(['updated_by']);
            $table->dropColumn('updated_by');

            $table->dropForeign(['deleted_by']);
            $table->dropColumn('deleted_by');
        });
        Schema::table('system_jobtype_mast', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');

            $table->dropForeign(['updated_by']);
            $table->dropColumn('updated_by');

            $table->dropForeign(['deleted_by']);
            $table->dropColumn('deleted_by');
        });

        Schema::table('system_leave_mast', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');

            $table->dropForeign(['updated_by']);
            $table->dropColumn('updated_by');

            $table->dropForeign(['deleted_by']);
            $table->dropColumn('deleted_by');
        });

        Schema::table('sundry_types', function (Blueprint $table) {

            $table->dropSoftDeletes();

            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');

            $table->dropForeign(['updated_by']);
            $table->dropColumn('updated_by');

            $table->dropForeign(['deleted_by']);
            $table->dropColumn('deleted_by');
        });

        Schema::table('system_office_mast', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');

            $table->dropForeign(['updated_by']);
            $table->dropColumn('updated_by');

            $table->dropForeign(['deleted_by']);
            $table->dropColumn('deleted_by');
        });

        Schema::table('provinces', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');

            $table->dropForeign(['updated_by']);
            $table->dropColumn('updated_by');

            $table->dropForeign(['deleted_by']);
            $table->dropColumn('deleted_by');
        });
        Schema::table('organization_setups', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');

            $table->dropForeign(['updated_by']);
            $table->dropColumn('updated_by');
        });

        Schema::table('system_post_mast', function (Blueprint $table) {
            $table->unsignedInteger('autho_id')->nullable();
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');

            $table->dropForeign(['updated_by']);
            $table->dropColumn('updated_by');

            $table->dropForeign(['deleted_by']);
            $table->dropColumn('deleted_by');
        });

        Schema::table('system_allwance_mast', function (Blueprint $table) {
            $table->unsignedInteger('autho_id')->nullable();
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');

            $table->dropForeign(['updated_by']);
            $table->dropColumn('updated_by');

            $table->dropForeign(['deleted_by']);
            $table->dropColumn('deleted_by');
        });
    }
}
