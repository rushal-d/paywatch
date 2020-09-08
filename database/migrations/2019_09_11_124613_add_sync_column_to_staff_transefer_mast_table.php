<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSyncColumnToStaffTranseferMastTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staff_transefer_mast', function (Blueprint $table) {
            $table->boolean('sync')->default(0)->nullable()->after('office_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('staff_transefer_mast', function (Blueprint $table) {
            $table->dropColumn('sync');
        });
    }
}
