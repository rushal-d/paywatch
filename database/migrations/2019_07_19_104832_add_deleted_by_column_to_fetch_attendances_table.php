<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeletedByColumnToFetchAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fetch_attendances', function (Blueprint $table) {
            $table->unsignedInteger('deleted_by')->after('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fetch_attendances', function (Blueprint $table) {
            $table->dropColumn('deleted_by');
        });
    }
}
