<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSystemLeaveMastAddFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('system_leave_mast', function (Blueprint $table) {
            $table->integer('leave_type')->default(1); //collapsible or non-collapsible
            $table->boolean('leave_earnability')->default(1);
            $table->float('leave_earnable_balance')->default(0);
            $table->integer('leave_earnable_period')->default(1); // monthly, fiscal year
            $table->integer('leave_earnable_type')->default(1); // flat, present days ratio, min present threshold, days from appointment, year from appointment
            $table->float('threshold_for_earnability')->nullable();
            $table->float('useability_count')->nullable();
            $table->integer('useability_count_unit')->nullable(); // days in fiscal year, time(s),days in month, times in month
            $table->boolean('allow_half_day')->default(0);
            $table->float('min_no_of_days_allowed_at_time')->nullable();
            $table->float('max_no_of_days_allowed_at_time')->nullable();
            $table->boolean('inclusive_public_holiday_weekend')->default(1);
            $table->integer('applicable_gender')->nullable(); // male female
            $table->boolean('is_paid')->default(1);
            $table->double('leave_extra_payment_amount', 10, 2)->nullable();
            $table->boolean('act_as_present_days')->default(1);
            $table->float('basic_salary_ratio')->default(100);
            $table->float('grade_ratio')->default(100);
            $table->float('allowance_ratio')->default(100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('system_leave_mast', function (Blueprint $table) {
            $table->dropColumn('leave_type'); //collapsible or non-collapsible
            $table->dropColumn('leave_earnability');
            $table->dropColumn('leave_earnable_balance');
            $table->dropColumn('leave_earnable_period'); // monthly, fiscal year
            $table->dropColumn('leave_earnable_type'); // flat, present days ratio, min present threshold, days from appointment, year from appointment
            $table->dropColumn('threshold_for_earnability');
            $table->dropColumn('useability_count');
            $table->dropColumn('useability_count_unit'); // days in fiscal year, time(s),days in month, times in month
            $table->dropColumn('allow_half_day');
            $table->dropColumn('min_no_of_days_allowed_at_time');
            $table->dropColumn('max_no_of_days_allowed_at_time');
            $table->dropColumn('inclusive_public_holiday_weekend');
            $table->dropColumn('applicable_gender')->nullable(); // male female
            $table->dropColumn('is_paid');
            $table->dropColumn('leave_extra_payment_amount');
            $table->dropColumn('act_as_present_days');
            $table->dropColumn('basic_salary_ratio');
            $table->dropColumn('grade_ratio');
            $table->dropColumn('allowance_ratio');
        });
    }
}
