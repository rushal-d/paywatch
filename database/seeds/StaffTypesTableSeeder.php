<?php

use Illuminate\Database\Seeder;

class StaffTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('staff_types')->delete();
        
        \DB::table('staff_types')->insert(array (
            0 => 
            array (
                'created_at' => '2020-03-18 00:00:00',
                'created_by' => 1,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'id' => 1,
                'staff_type_code' => 0,
                'staff_type_title' => 'BBSM',
                'updated_at' => '2020-03-18 00:00:00',
                'updated_by' => 1,
            ),
            1 => 
            array (
                'created_at' => '2020-03-18 00:00:00',
                'created_by' => 1,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'id' => 2,
                'staff_type_code' => 1,
                'staff_type_title' => 'Guard BBSM',
                'updated_at' => '2020-03-18 00:00:00',
                'updated_by' => 1,
            ),
            2 => 
            array (
                'created_at' => '2020-03-18 00:00:00',
                'created_by' => 1,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'id' => 3,
                'staff_type_code' => 2,
                'staff_type_title' => 'Company',
                'updated_at' => '2020-03-18 00:00:00',
                'updated_by' => 1,
            ),
            3 => 
            array (
                'created_at' => '2020-03-18 00:00:00',
                'created_by' => 1,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'id' => 4,
                'staff_type_code' => 3,
                'staff_type_title' => 'Company Guard',
                'updated_at' => '2020-03-18 00:00:00',
                'updated_by' => 1,
            ),
            4 => 
            array (
                'created_at' => '2020-03-18 00:00:00',
                'created_by' => 1,
                'deleted_at' => NULL,
                'deleted_by' => NULL,
                'id' => 5,
                'staff_type_code' => 4,
                'staff_type_title' => 'BBSM Not In Payroll',
                'updated_at' => '2020-03-18 00:00:00',
                'updated_by' => 1,
            ),
        ));
        
        
    }
}