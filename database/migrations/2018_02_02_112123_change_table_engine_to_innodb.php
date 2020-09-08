<?php
use Illuminate\Database\Migrations\Migration;

class ChangeTableEngineToInnodb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tables = [
            'payroll_details',
            'attendance_details',
            'attendance_details_sum',
            'migrations',
            'staff_main_mast',
            'system_edu_mast',
            'trans_attendance',
            'leave_history',
            'system_dist_mast',
            'system_tdsdetails_mast',
            'users',
            'roles',
            'system_allwance_mast',
            'system_post_mast',
            'trans_vehical_loan',
            'grades',
            'role_user',
            'staff_workschedule_mast	',
            'system_office_mast',
            'trans_tds',
            'generated_salary',
            'pictures',
            'staff_types',
            'system_leave_mast',
            'trans_sundry',
            'fiscal_year',
            'permissions',
            'staff_transefer_mast',
            'system_jobtype_mast',
            'trans_profund',
            'calender_holiday',
            'permission_role',
            'staff_salary_mast',
            'system_holiday_mast',
            'trans_leave',
            'bank_mast',
            'payroll_details',
            'staff_payment_mast',
            'trans_house_loan',
            'attendance_details_sum',
            'staff_nominee_mast',
            'trans_geatuty',
        ];
        foreach ($tables as $table) {
            DB::statement('ALTER TABLE ' . $table . ' ENGINE = InnoDB');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /*$tables = [
            'payroll_details',
            'attendance_details',
            'attendance_details_sum',
            'migrations',
            'staff_main_mast',
            'system_edu_mast',
            'trans_attendance',
            'leave_history',
            'system_dist_mast',
            'system_tdsdetails_mast',
            'users',
            'roles',
            'system_allwance_mast',
            'system_post_mast',
            'trans_vehical_loan',
            'grades',
            'role_user',
            'staff_workschedule_mast	',
            'system_office_mast',
            'trans_tds',
            'generated_salary',
            'pictures',
            'staff_types',
            'system_leave_mast',
            'trans_sundry',
            'fiscal_year',
            'permissions',
            'staff_transefer_mast',
            'system_jobtype_mast',
            'trans_profund',
            'calender_holiday',
            'permission_role',
            'staff_salary_mast',
            'system_holiday_mast',
            'trans_leave',
            'bank_mast',
            'payroll_details',
            'staff_payment_mast',
            'trans_house_loan',
            'attendance_details_sum',
            'staff_nominee_mast',
            'trans_geatuty',
        ];
        foreach ($tables as $table) {
            DB::statement('ALTER TABLE ' . $table . ' ENGINE = MyISAM');
        }*/
    }
}
