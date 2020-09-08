<?php

use Illuminate\Database\Seeder;

class RoleUserTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('role_user')->delete();
        
        \DB::table('role_user')->insert(array (
            0 => 
            array (
                'user_id' => 1,
                'role_id' => 1,
            ),
            1 => 
            array (
                'user_id' => 9,
                'role_id' => 1,
            ),
            2 => 
            array (
                'user_id' => 10,
                'role_id' => 1,
            ),
            3 => 
            array (
                'user_id' => 13,
                'role_id' => 1,
            ),
            4 => 
            array (
                'user_id' => 20,
                'role_id' => 1,
            ),
            5 => 
            array (
                'user_id' => 30,
                'role_id' => 1,
            ),
            6 => 
            array (
                'user_id' => 11,
                'role_id' => 4,
            ),
            7 => 
            array (
                'user_id' => 15,
                'role_id' => 4,
            ),
            8 => 
            array (
                'user_id' => 16,
                'role_id' => 4,
            ),
            9 => 
            array (
                'user_id' => 17,
                'role_id' => 4,
            ),
            10 => 
            array (
                'user_id' => 18,
                'role_id' => 4,
            ),
            11 => 
            array (
                'user_id' => 19,
                'role_id' => 4,
            ),
            12 => 
            array (
                'user_id' => 2,
                'role_id' => 5,
            ),
            13 => 
            array (
                'user_id' => 14,
                'role_id' => 5,
            ),
            14 => 
            array (
                'user_id' => 21,
                'role_id' => 5,
            ),
            15 => 
            array (
                'user_id' => 22,
                'role_id' => 5,
            ),
            16 => 
            array (
                'user_id' => 23,
                'role_id' => 5,
            ),
            17 => 
            array (
                'user_id' => 24,
                'role_id' => 5,
            ),
            18 => 
            array (
                'user_id' => 25,
                'role_id' => 5,
            ),
            19 => 
            array (
                'user_id' => 26,
                'role_id' => 5,
            ),
            20 => 
            array (
                'user_id' => 27,
                'role_id' => 5,
            ),
            21 => 
            array (
                'user_id' => 29,
                'role_id' => 5,
            ),
            22 => 
            array (
                'user_id' => 28,
                'role_id' => 6,
            ),
        ));
        
        
    }
}