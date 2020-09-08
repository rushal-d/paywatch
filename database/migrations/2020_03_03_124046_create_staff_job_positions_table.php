<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffJobPositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('system_post_mast', function (Blueprint $table) {

            $table->boolean('active')->default(1);
            $table->unsignedInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('post_id')->on('system_post_mast');
            $table->dropColumn('status_id');
            $table->dropColumn('grade_amount');

        });
        Schema::table('system_post_mast', function (Blueprint $table) {
            $table->string('grade_amount',10,2)->default(0);
        });
        Schema::create('staff_job_positions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('staff_central_id');
            $table->foreign('staff_central_id')->references('id')->on('staff_main_mast');

            $table->unsignedInteger('post_id');
            $table->foreign('post_id')->references('post_id')->on('system_post_mast');

            $table->date('effective_from_date');
            $table->string('effective_from_date_np');

            $table->date('effective_to_date')->nullable();
            $table->string('effective_to_date_np')->nullable();

            $table->unsignedInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->unsignedInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staff_job_positions');
        Schema::table('system_post_mast', function (Blueprint $table) {
            $table->dropColumn('active');
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
            $table->integer('status_id')->nullable();
        });
    }
}
