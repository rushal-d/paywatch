<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropForeignKeyStaffForStaffTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('staff_types');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('staff_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('staff_central_id');
            $table->string('value');
            $table->timestamps();
        });
    }
}
