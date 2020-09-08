<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSystemLeaveMastAddJobTypeIdAndAllowNegative extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('system_leave_mast', function (Blueprint $table) {
            $table->boolean('allow_negative')->default(0);
            $table->unsignedInteger('job_type_id')->nullable();
            $table->foreign('job_type_id')->references('jobtype_id')->on('system_jobtype_mast');
            $table->string('leave_code', 10)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('system_leave_mast', function (Blueprint $table) {
            $table->dropForeign(['job_type_id']);
            $table->dropColumn('job_type_id');
            $table->dropColumn('allow_negative');
            $table->string('leave_code', 2)->change();
        });
    }
}
