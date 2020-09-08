<?php

use Illuminate\Database\Seeder;

class SystemLeaveMastTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('system_leave_mast')->delete();
        
        \DB::table('system_leave_mast')->insert(array (
            0 => 
            array (
                'created_at' => '2018-01-05 18:11:45',
                'deleted_at' => '2018-01-08 17:57:45',
                'initial_setup' => 0,
                'leave_code' => '01',
                'leave_id' => 1,
                'leave_name' => 'Home Leave',
                'max_days' => 15,
                'no_of_days' => 0,
                'updated_at' => '2018-01-08 17:57:45',
            ),
            1 => 
            array (
                'created_at' => '2018-01-05 18:12:13',
                'deleted_at' => '2018-01-08 17:57:48',
                'initial_setup' => 0,
                'leave_code' => '02',
                'leave_id' => 2,
                'leave_name' => 'Sick Leave',
                'max_days' => 20,
                'no_of_days' => 0,
                'updated_at' => '2018-01-08 17:57:48',
            ),
            2 => 
            array (
                'created_at' => '2018-01-06 16:39:11',
                'deleted_at' => '2018-01-08 17:57:52',
                'initial_setup' => 0,
                'leave_code' => '03',
                'leave_id' => 3,
                'leave_name' => 'Weekend Leave',
                'max_days' => 52,
                'no_of_days' => 0,
                'updated_at' => '2018-01-08 17:57:52',
            ),
            3 => 
            array (
                'created_at' => '2018-01-06 16:39:47',
                'deleted_at' => '2018-01-08 17:57:56',
                'initial_setup' => 0,
                'leave_code' => '04',
                'leave_id' => 4,
            'leave_name' => 'Satta Bida (Public Holidays)',
                'max_days' => 5,
                'no_of_days' => 0,
                'updated_at' => '2018-01-08 17:57:56',
            ),
            4 => 
            array (
                'created_at' => '2018-01-08 18:01:11',
                'deleted_at' => NULL,
                'initial_setup' => 0,
                'leave_code' => '1',
                'leave_id' => 5,
                'leave_name' => 'Weekend Holiday',
                'max_days' => 5,
                'no_of_days' => 0,
                'updated_at' => '2018-01-08 18:01:11',
            ),
            5 => 
            array (
                'created_at' => '2018-01-08 18:01:36',
                'deleted_at' => NULL,
                'initial_setup' => 0,
                'leave_code' => '2',
                'leave_id' => 6,
                'leave_name' => 'Public Holiday',
                'max_days' => 4,
                'no_of_days' => 0,
                'updated_at' => '2018-01-08 18:01:36',
            ),
            6 => 
            array (
                'created_at' => '2018-01-08 18:02:03',
                'deleted_at' => NULL,
                'initial_setup' => 1,
                'leave_code' => '3',
                'leave_id' => 7,
                'leave_name' => 'Home Leave',
                'max_days' => 100,
                'no_of_days' => 18,
                'updated_at' => '2019-07-19 17:26:26',
            ),
            7 => 
            array (
                'created_at' => '2018-01-08 18:02:19',
                'deleted_at' => NULL,
                'initial_setup' => 1,
                'leave_code' => '4',
                'leave_id' => 8,
                'leave_name' => 'Sick Leave',
                'max_days' => 100,
                'no_of_days' => 15,
                'updated_at' => '2019-07-19 17:26:35',
            ),
            8 => 
            array (
                'created_at' => '2018-01-08 18:03:00',
                'deleted_at' => NULL,
                'initial_setup' => 1,
                'leave_code' => '5',
                'leave_id' => 9,
                'leave_name' => 'Maternity Leave',
                'max_days' => 104,
                'no_of_days' => 0,
                'updated_at' => '2018-01-08 18:03:00',
            ),
            9 => 
            array (
                'created_at' => '2018-01-08 18:03:35',
                'deleted_at' => NULL,
                'initial_setup' => 1,
                'leave_code' => '6',
                'leave_id' => 10,
                'leave_name' => 'Maternity Care Leave',
                'max_days' => 30,
                'no_of_days' => 0,
                'updated_at' => '2018-01-08 18:03:35',
            ),
            10 => 
            array (
                'created_at' => '2018-01-08 18:04:24',
                'deleted_at' => NULL,
                'initial_setup' => 1,
                'leave_code' => '7',
                'leave_id' => 11,
                'leave_name' => 'Funeral Leave',
                'max_days' => 30,
                'no_of_days' => 0,
                'updated_at' => '2018-01-08 18:04:24',
            ),
            11 => 
            array (
                'created_at' => '2018-01-08 18:05:09',
                'deleted_at' => NULL,
                'initial_setup' => 1,
                'leave_code' => '8',
                'leave_id' => 12,
                'leave_name' => 'Substitute Leave',
                'max_days' => 4,
                'no_of_days' => 0,
                'updated_at' => '2018-01-08 18:05:09',
            ),
            12 => 
            array (
                'created_at' => '2018-01-08 18:08:15',
                'deleted_at' => NULL,
                'initial_setup' => 1,
                'leave_code' => '9',
                'leave_id' => 13,
                'leave_name' => 'Leave Without Pay',
                'max_days' => 30,
                'no_of_days' => 0,
                'updated_at' => '2018-01-08 18:08:15',
            ),
            13 => 
            array (
                'created_at' => '2018-01-08 18:08:57',
                'deleted_at' => NULL,
                'initial_setup' => 1,
                'leave_code' => '10',
                'leave_id' => 14,
                'leave_name' => 'Maternity Leave Without Pay',
                'max_days' => 30,
                'no_of_days' => 0,
                'updated_at' => '2018-01-08 18:08:57',
            ),
        ));
        
        
    }
}