<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTablesEngineToInnodbTwo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tables = ['castes', 'provinces', 'religions', 'system_dist_mast', 'shifts'];
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
        /*$tables = ['castes', 'provinces', 'religions', 'system_dist_mast', 'shifts'];

        foreach ($tables as $table) {
            DB::statement('ALTER TABLE ' . $table . ' ENGINE = MyISAM');
        }*/
    }
}
