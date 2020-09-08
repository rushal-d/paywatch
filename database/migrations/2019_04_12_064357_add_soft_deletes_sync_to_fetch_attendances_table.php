<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoftDeletesSyncToFetchAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fetch_attendances', function (Blueprint $table) {
            $table->boolean('sync')->after('status')->default(0);
            $table->string('punchout_datetime_np')->nullable(true)->change();
            $table->dateTime('punchout_datetime')->nullable(true)->change();
            $table->softDeletes();
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
            $table->dropSoftDeletes();
            $table->dropColumn('sync');
            $table->string('punchout_datetime_np')->nullable(false)->change();
            $table->dateTime('punchout_datetime')->nullable(false)->change();
        });
    }
}
