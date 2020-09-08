<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainingDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('training_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('staff_central_id');
            $table->foreign('staff_central_id')->references('id')->on('staff_main_mast');

            $table->string('training_organization_name');
            $table->string('training_title');
            $table->string('training_category')->nullable();
            $table->date('training_start_date')->nullable();
            $table->string('training_start_date_np')->nullable();
            $table->date('training_end_date')->nullable();
            $table->string('training_end_date_np')->nullable();
            $table->string('result')->nullable();
            $table->string('training_main_subject')->nullable();
            $table->string('training_description')->nullable();

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
        Schema::dropIfExists('training_details');
    }
}
