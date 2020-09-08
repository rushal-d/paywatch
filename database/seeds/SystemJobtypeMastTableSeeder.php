<?php

use Illuminate\Database\Seeder;

class SystemJobtypeMastTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('system_jobtype_mast')->delete();
        
        \DB::table('system_jobtype_mast')->insert(array (
            0 => 
            array (
                'jobtype_id' => 1,
                'jobtype_name' => 'Permanent',
                'effect_date' => '2075-05-14',
                'effect_date_np' => NULL,
                'gratuity' => 10.0,
                'profund_per' => 10.0,
                'profund_contri_per' => 10.0,
                'created_at' => '2018-08-30 06:04:44',
                'updated_at' => '2018-08-30 06:04:44',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'jobtype_id' => 2,
                'jobtype_name' => 'Non Permanent',
                'effect_date' => '2075-05-14',
                'effect_date_np' => NULL,
                'gratuity' => 10.0,
                'profund_per' => 10.0,
                'profund_contri_per' => 10.0,
                'created_at' => '2018-08-30 06:04:58',
                'updated_at' => '2018-08-30 06:04:58',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'jobtype_id' => 3,
                'jobtype_name' => 'Contract_1',
                'effect_date' => '2075-05-14',
                'effect_date_np' => NULL,
                'gratuity' => 10.0,
                'profund_per' => 10.0,
                'profund_contri_per' => 10.0,
                'created_at' => '2018-08-30 06:05:09',
                'updated_at' => '2018-08-30 06:05:09',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'jobtype_id' => 4,
                'jobtype_name' => 'Contract_2',
                'effect_date' => '2075-05-14',
                'effect_date_np' => NULL,
                'gratuity' => 10.0,
                'profund_per' => 10.0,
                'profund_contri_per' => 10.0,
                'created_at' => '2018-08-30 06:05:18',
                'updated_at' => '2018-08-30 06:05:18',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'jobtype_id' => 5,
                'jobtype_name' => 'Trainee',
                'effect_date' => '2075-05-14',
                'effect_date_np' => NULL,
                'gratuity' => 10.0,
                'profund_per' => 10.0,
                'profund_contri_per' => 10.0,
                'created_at' => '2018-08-30 06:05:25',
                'updated_at' => '2018-08-30 06:05:25',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}