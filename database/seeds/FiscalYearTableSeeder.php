<?php

use Illuminate\Database\Seeder;

class FiscalYearTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('fiscal_year')->delete();
        
        \DB::table('fiscal_year')->insert(array (
            0 => 
            array (
                'id' => 1,
                'fiscal_start_date' => '2016-07-16',
                'fiscal_end_date' => '2017-07-15',
                'fiscal_start_date_np' => '2073-04-01',
                'fiscal_end_date_np' => '2074-03-31',
                'fiscal_code' => '2073/74',
                'fiscal_status' => '0',
                'created_at' => '2018-08-23 11:50:11',
                'updated_at' => '2018-08-23 11:50:11',
                'present_days' => 317,
            ),
            1 => 
            array (
                'id' => 2,
                'fiscal_start_date' => '2017-07-16',
                'fiscal_end_date' => '2018-07-16',
                'fiscal_start_date_np' => '2074-04-01',
                'fiscal_end_date_np' => '2075-03-32',
                'fiscal_code' => '2074/75',
                'fiscal_status' => '0',
                'created_at' => '2018-08-23 11:50:42',
                'updated_at' => '2019-04-15 23:59:11',
                'present_days' => 317,
            ),
            2 => 
            array (
                'id' => 3,
                'fiscal_start_date' => '2018-07-17',
                'fiscal_end_date' => '2019-07-16',
                'fiscal_start_date_np' => '2075-04-01',
                'fiscal_end_date_np' => '2076-03-31',
                'fiscal_code' => '2075/76',
                'fiscal_status' => '0',
                'created_at' => '2018-08-23 11:51:07',
                'updated_at' => '2019-07-19 19:51:50',
                'present_days' => 317,
            ),
            3 => 
            array (
                'id' => 4,
                'fiscal_start_date' => '2019-07-17',
                'fiscal_end_date' => '2020-07-15',
                'fiscal_start_date_np' => '2076-04-01',
                'fiscal_end_date_np' => '2077-03-31',
                'fiscal_code' => '2076/77',
                'fiscal_status' => '1',
                'created_at' => '2019-07-17 17:41:15',
                'updated_at' => '2019-08-29 10:33:20',
                'present_days' => 315,
            ),
            4 => 
            array (
                'id' => 5,
                'fiscal_start_date' => '2020-07-16',
                'fiscal_end_date' => '2021-07-15',
                'fiscal_start_date_np' => '2077-04-01',
                'fiscal_end_date_np' => '2078-03-31',
                'fiscal_code' => '2077/78',
                'fiscal_status' => '0',
                'created_at' => '2019-08-18 12:54:23',
                'updated_at' => '2019-08-18 12:54:23',
                'present_days' => 315,
            ),
            5 => 
            array (
                'id' => 6,
                'fiscal_start_date' => '2021-07-16',
                'fiscal_end_date' => '2022-07-16',
                'fiscal_start_date_np' => '2078-04-01',
                'fiscal_end_date_np' => '2079-03-32',
                'fiscal_code' => '2078/79',
                'fiscal_status' => '0',
                'created_at' => '2019-08-18 12:55:10',
                'updated_at' => '2019-08-18 12:55:10',
                'present_days' => 315,
            ),
            6 => 
            array (
                'id' => 7,
                'fiscal_start_date' => NULL,
                'fiscal_end_date' => NULL,
                'fiscal_start_date_np' => '2079-04-01',
                'fiscal_end_date_np' => '2078-03-32',
                'fiscal_code' => '2079/78',
                'fiscal_status' => '0',
                'created_at' => '2019-08-18 12:55:49',
                'updated_at' => '2019-08-18 12:55:49',
                'present_days' => 315,
            ),
        ));
        
        
    }
}