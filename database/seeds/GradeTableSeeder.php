<?php

use Illuminate\Database\Seeder;
use App\GradeModel;

class GradeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('grades')->delete();
    	for($a= 1; $a <= 10; $a++){
		    GradeModel::create( [
			    'value'=>$a
		    ] );
	    }
    }
}
