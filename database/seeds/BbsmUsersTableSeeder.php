<?php

use Illuminate\Database\Seeder;

class BbsmUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    DB::table('users')->insert([
		    'name' => 'bbsmheadoffice',
		    'email' => 'bbsmheadoffice@bbsm.com.np',
		    'password' => bcrypt('bbsm@ho777'),
	    ]);

	    DB::table('users')->insert([
		    'name' => 'bbsmadmin',
		    'email' => 'bbsmadmin@bbsm.com.np',
		    'password' => bcrypt('bbsmadmin777'),
	    ]);
    }
}
