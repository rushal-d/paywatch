<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationMastShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_mast_shifts', function (Blueprint $table) {
            $table->increments('id');
            $table->date('effective_from');
            $table->string('effective_from_np');
            $table->time('sunday_punch_in')->nullable();
            $table->time('sunday_punch_out')->nullable();
            $table->time('monday_punch_in')->nullable();
            $table->time('monday_punch_out')->nullable();
            $table->time('tuesday_punch_in')->nullable();
            $table->time('tuesday_punch_out')->nullable();
            $table->time('wednesday_punch_in')->nullable();
            $table->time('wednesday_punch_out')->nullable();
            $table->time('thursday_punch_in')->nullable();
            $table->time('thursday_punch_out')->nullable();
            $table->time('friday_punch_in')->nullable();
            $table->time('friday_punch_out')->nullable();
            $table->time('saturday_punch_in')->nullable();
            $table->time('saturday_punch_out')->nullable();

            $table->unsignedInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->unsignedInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('organization_setups', function (Blueprint $table) {
            $table->integer('overtime_calculation_type')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organization_mast_shifts');
        Schema::table('organization_setups', function (Blueprint $table) {
            $table->dropColumn('overtime_calculation_type');
        });
    }
}
