<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterIntToFloatLeaveBalance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leave_balance', function (Blueprint $table) {
	        $table->float('consumption')->change();
	        $table->float('earned')->change();
	        $table->float('balance')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leave_balance', function (Blueprint $table) {
	        $table->integer('consumption')->change();
	        $table->integer('earned')->change();
	        $table->integer('balance')->change();
        });
    }
}
