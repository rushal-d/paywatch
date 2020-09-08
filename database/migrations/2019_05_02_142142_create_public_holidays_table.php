<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicHolidaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('public_holidays', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('branch_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->date('from_date');
            $table->string('from_date_np');
            $table->date('to_date');
            $table->string('to_date_np');
            $table->integer('gender')->nullable();
            $table->foreign('branch_id')->references('office_id')->on('system_office_mast');
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by')->nullable();
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
        Schema::dropIfExists('public_holidays');
    }
}
