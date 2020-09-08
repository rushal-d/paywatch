<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeMainIdToNullableForStaffMainMastTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staff_main_mast', function (Blueprint $table) {
            $table->unsignedInteger('main_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('staff_main_mast', function (Blueprint $table) {
            $table->unsignedInteger('main_id')->nullable(false)->change();
        });
    }
}
