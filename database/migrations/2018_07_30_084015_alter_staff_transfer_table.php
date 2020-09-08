<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterStaffTransferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staff_transefer_mast', function (Blueprint $table) {
            $table->renameColumn('to_date', 'transfer_date');
            $table->renameColumn('to_date_np', 'transfer_date_np');
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
            $table->renameColumn('transfer_date', 'to_date');
            $table->renameColumn('transfer_date_np', 'to_date_np');
        });
    }
}
