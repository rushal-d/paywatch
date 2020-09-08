<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_grades', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('staff_central_id')->onDelete('cascade');
            $table->foreign('staff_central_id')->references('id')->on('staff_main_mast');

            $table->unsignedInteger('grade_id')->onDelete('cascade');
            $table->foreign('grade_id')->references('id')->on('grades');

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
        Schema::dropIfExists('staff_grades');
    }
}
