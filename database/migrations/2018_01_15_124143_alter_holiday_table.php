<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterHolidayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('system_holiday_mast', function (Blueprint $table) {
	        $table->date("from_date")->nullable();
	        $table->date("to_date")->nullable();
	        $table->string("from_date_np")->nullable();
	        $table->string("to_date_np")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('system_holiday_mast', function (Blueprint $table) {
	        $table->removeColumn("from_date");
	        $table->removeColumn("to_date");
	        $table->removeColumn("from_date_np");
	        $table->removeColumn("to_date_np");
        });
    }
}
