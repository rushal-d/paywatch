<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('file_type');
            $table->string('file_section');
            $table->text('description')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->unsignedInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('staff_file', function (Blueprint $table) {
            $table->unsignedInteger('file_type_id')->nullable();
            $table->foreign('file_type_id')->references('id')->on('file_types');
        });

        Schema::table('staff_main_mast', function (Blueprint $table) {
            $table->string('uuid')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('staff_file', function (Blueprint $table) {
            $table->dropForeign(['file_type_id']);
            $table->dropColumn('file_type_id');
        });

        Schema::table('staff_main_mast', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });

        Schema::dropIfExists('file_types');


    }
}
