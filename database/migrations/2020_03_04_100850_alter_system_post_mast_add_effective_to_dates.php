<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSystemPostMastAddEffectiveToDates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('system_post_mast', function (Blueprint $table) {
            $table->date('effective_to_date')->nullable();
            $table->string('effective_to_date_np')->nullable();
        });
        Schema::table('staff_job_positions', function (Blueprint $table) {
            $table->boolean('is_system_created')->default(0);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('staff_job_positions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('system_post_mast', function (Blueprint $table) {
            $table->dropColumn('effective_to_date');
            $table->dropColumn('effective_to_date_np');
        });
        Schema::table('staff_job_positions', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
            $table->dropColumn('is_system_created');
        });
    }
}
