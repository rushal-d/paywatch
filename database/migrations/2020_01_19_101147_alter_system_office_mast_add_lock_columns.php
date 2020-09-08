<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSystemOfficeMastAddLockColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('system_office_mast', function (Blueprint $table) {
            $table->tinyInteger('sync_lock_shift')->default(0);
            $table->tinyInteger('sync_lock_staff')->default(0);
            $table->tinyInteger('sync_lock_attendance')->default(0);
            $table->tinyInteger('sync_lock_fingerprint')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('system_office_mast', function (Blueprint $table) {
            $table->dropColumn('sync_lock_shift');
            $table->dropColumn('sync_lock_staff');
            $table->dropColumn('sync_lock_attendance');
            $table->dropColumn('sync_lock_fingerprint');
        });
    }
}
