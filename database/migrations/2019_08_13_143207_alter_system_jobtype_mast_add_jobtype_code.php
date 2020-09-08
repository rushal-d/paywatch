<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSystemJobtypeMastAddJobtypeCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('system_jobtype_mast', function (Blueprint $table) {
            $table->string('jobtype_code')->after('jobtype_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('system_jobtype_mast', function (Blueprint $table) {
            $table->dropColumn('jobtype_code');
        });
    }
}
