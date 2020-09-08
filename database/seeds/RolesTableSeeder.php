<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('roles')->delete();
        
        \DB::table('roles')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Administrator',
                'display_name' => 'Adminstrator',
                'description' => 'Admin',
                'created_at' => '2018-07-24 10:37:01',
                'updated_at' => '2018-07-24 10:37:01',
            ),
            1 => 
            array (
                'id' => 3,
                'name' => 'Editor',
                'display_name' => 'Editor',
                'description' => 'Editor',
                'created_at' => '2018-07-24 10:38:47',
                'updated_at' => '2018-07-24 10:38:47',
            ),
            2 => 
            array (
                'id' => 4,
                'name' => 'Supervisor',
                'display_name' => 'Supervisor',
            'description' => 'Department (Floor) Supervisor',
                'created_at' => '2019-04-17 20:04:30',
                'updated_at' => '2019-04-17 20:04:30',
            ),
            3 => 
            array (
                'id' => 5,
                'name' => 'Attendance Manager',
                'display_name' => 'Attendance Manager',
                'description' => NULL,
                'created_at' => '2019-04-21 21:52:41',
                'updated_at' => '2019-04-21 21:52:41',
            ),
            4 => 
            array (
                'id' => 6,
                'name' => 'Branch Level',
                'display_name' => 'Branch Level',
                'description' => NULL,
                'created_at' => '2019-09-26 11:46:32',
                'updated_at' => '2019-09-26 11:46:32',
            ),
        ));
        
        
    }
}