<?php

use Illuminate\Database\Seeder;

class SystemPostMastTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('system_post_mast')->delete();
        
        \DB::table('system_post_mast')->insert(array (
            0 => 
            array (
                'post_id' => 1,
                'post_title' => 'SUPERVISOR',
                'basic_salary' => 6205.0,
                'effect_date' => '2075-05-14',
                'effect_date_np' => NULL,
                'grade_amount' => 0,
                'grade_id' => 1,
                'autho_id' => NULL,
                'status_id' => NULL,
                'created_at' => '2018-08-30 06:10:16',
                'updated_at' => '2018-08-30 06:10:16',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'post_id' => 2,
                'post_title' => 'SALESKEEPER',
                'basic_salary' => 6205.0,
                'effect_date' => '2075-05-14',
                'effect_date_np' => NULL,
                'grade_amount' => 0,
                'grade_id' => 2,
                'autho_id' => NULL,
                'status_id' => NULL,
                'created_at' => '2018-08-30 06:10:42',
                'updated_at' => '2018-08-30 06:10:42',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'post_id' => 3,
                'post_title' => 'ASST. CASHIER',
                'basic_salary' => 6205.0,
                'effect_date' => '2075-05-14',
                'effect_date_np' => NULL,
                'grade_amount' => 0,
                'grade_id' => 2,
                'autho_id' => NULL,
                'status_id' => NULL,
                'created_at' => '2018-08-30 06:11:01',
                'updated_at' => '2018-08-30 06:11:01',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'post_id' => 4,
                'post_title' => 'HOUSEKEEPER',
                'basic_salary' => 6205.0,
                'effect_date' => '2075-05-14',
                'effect_date_np' => NULL,
                'grade_amount' => 0,
                'grade_id' => 2,
                'autho_id' => NULL,
                'status_id' => NULL,
                'created_at' => '2018-08-30 06:11:29',
                'updated_at' => '2018-08-30 06:11:29',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'post_id' => 5,
                'post_title' => 'STOREKEEPER',
                'basic_salary' => 6205.0,
                'effect_date' => '2075-05-14',
                'effect_date_np' => NULL,
                'grade_amount' => 0,
                'grade_id' => 2,
                'autho_id' => NULL,
                'status_id' => NULL,
                'created_at' => '2018-08-30 06:11:53',
                'updated_at' => '2018-08-30 06:11:53',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'post_id' => 6,
                'post_title' => 'ASST. SUPERVISOR',
                'basic_salary' => 6205.0,
                'effect_date' => '2075-05-14',
                'effect_date_np' => NULL,
                'grade_amount' => 0,
                'grade_id' => 2,
                'autho_id' => NULL,
                'status_id' => NULL,
                'created_at' => '2018-08-30 06:12:18',
                'updated_at' => '2018-08-30 06:12:18',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'post_id' => 7,
                'post_title' => 'SR. SALESKEEPER',
                'basic_salary' => 6205.0,
                'effect_date' => '2075-05-14',
                'effect_date_np' => NULL,
                'grade_amount' => 0,
                'grade_id' => 3,
                'autho_id' => NULL,
                'status_id' => NULL,
                'created_at' => '2018-08-30 06:12:37',
                'updated_at' => '2018-08-30 06:12:37',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'post_id' => 8,
                'post_title' => 'SALES ASSISTANT',
                'basic_salary' => 6205.0,
                'effect_date' => '2075-05-14',
                'effect_date_np' => NULL,
                'grade_amount' => 0,
                'grade_id' => 3,
                'autho_id' => NULL,
                'status_id' => NULL,
                'created_at' => '2018-08-30 06:13:07',
                'updated_at' => '2018-08-30 06:13:07',
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'post_id' => 9,
                'post_title' => 'ASST. SALESKEEPER',
                'basic_salary' => 6205.0,
                'effect_date' => '2075-05-15',
                'effect_date_np' => NULL,
                'grade_amount' => 0,
                'grade_id' => 4,
                'autho_id' => NULL,
                'status_id' => NULL,
                'created_at' => '2018-08-31 05:19:52',
                'updated_at' => '2018-08-31 05:19:52',
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'post_id' => 10,
                'post_title' => 'CASHIER',
                'basic_salary' => 6205.0,
                'effect_date' => '2075-05-15',
                'effect_date_np' => NULL,
                'grade_amount' => 0,
                'grade_id' => 4,
                'autho_id' => NULL,
                'status_id' => NULL,
                'created_at' => '2018-08-31 05:20:07',
                'updated_at' => '2018-08-31 05:20:07',
                'deleted_at' => NULL,
            ),
            10 => 
            array (
                'post_id' => 11,
                'post_title' => 'ASST.CASHIER',
                'basic_salary' => 6205.0,
                'effect_date' => '2075-05-15',
                'effect_date_np' => NULL,
                'grade_amount' => 0,
                'grade_id' => 2,
                'autho_id' => NULL,
                'status_id' => NULL,
                'created_at' => '2018-08-31 05:20:22',
                'updated_at' => '2018-08-31 05:22:41',
                'deleted_at' => '2018-08-31 05:22:41',
            ),
            11 => 
            array (
                'post_id' => 12,
                'post_title' => 'TRAINEE',
                'basic_salary' => 6205.0,
                'effect_date' => '2075-05-15',
                'effect_date_np' => NULL,
                'grade_amount' => 0,
                'grade_id' => 2,
                'autho_id' => NULL,
                'status_id' => NULL,
                'created_at' => '2018-08-31 05:21:15',
                'updated_at' => '2018-08-31 05:21:15',
                'deleted_at' => NULL,
            ),
            12 => 
            array (
                'post_id' => 13,
                'post_title' => 'CUSTOMER SERVICE',
                'basic_salary' => 8455.0,
                'effect_date' => '2074-12-01',
                'effect_date_np' => NULL,
                'grade_amount' => 0,
                'grade_id' => 1,
                'autho_id' => NULL,
                'status_id' => NULL,
                'created_at' => '2019-04-11 07:16:52',
                'updated_at' => '2019-04-11 07:16:52',
                'deleted_at' => NULL,
            ),
            13 => 
            array (
                'post_id' => 14,
                'post_title' => 'Office Asst.',
                'basic_salary' => 8455.0,
                'effect_date' => '2074-12-01',
                'effect_date_np' => NULL,
                'grade_amount' => 0,
                'grade_id' => 1,
                'autho_id' => NULL,
                'status_id' => NULL,
                'created_at' => '2019-04-11 08:26:03',
                'updated_at' => '2019-04-11 08:26:03',
                'deleted_at' => NULL,
            ),
            14 => 
            array (
                'post_id' => 15,
                'post_title' => 'IT',
                'basic_salary' => 4855.0,
                'effect_date' => '2074-12-01',
                'effect_date_np' => NULL,
                'grade_amount' => 0,
                'grade_id' => 1,
                'autho_id' => NULL,
                'status_id' => NULL,
                'created_at' => '2019-04-11 08:28:27',
                'updated_at' => '2019-04-11 08:28:27',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}