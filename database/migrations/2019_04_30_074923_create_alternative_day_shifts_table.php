<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlternativeDayShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alternative_day_shifts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('staff_central_id');
            $table->foreign('staff_central_id')->references('id')->on('staff_main_mast');

            $table->integer('day')->nullable();

            $table->unsignedInteger('shift_id');
            $table->foreign('shift_id')->references('id')->on('shifts');

            $table->unsignedInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');

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
        Schema::dropIfExists('alternative_day_shifts');
    }
}
