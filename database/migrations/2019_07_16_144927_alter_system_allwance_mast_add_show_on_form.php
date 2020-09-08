<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSystemAllwanceMastAddShowOnForm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('system_allwance_mast', function (Blueprint $table) {
            $table->boolean('show_on_form')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('system_allwance_mast', function (Blueprint $table) {
            $table->dropColumn('show_on_form');
        });
    }
}
