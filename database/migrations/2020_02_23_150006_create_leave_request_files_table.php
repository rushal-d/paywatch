<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeaveRequestFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_request_files', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('leave_request_id');
            $table->foreign('leave_request_id')->references('id')->on('leave_requests')->onDelete('cascade');

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
        Schema::dropIfExists('leave_request_files');
    }
}
