<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainingDetailFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('training_detail_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('training_detail_id')->onDelete('cascade');
            $table->foreign('training_detail_id')->references('id')->on('training_details');

            $table->unsignedInteger('staff_file_id')->onDelete('cascade');
            $table->foreign('staff_file_id')->references('id')->on('staff_file');
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
        Schema::dropIfExists('training_detail_files');
    }
}
