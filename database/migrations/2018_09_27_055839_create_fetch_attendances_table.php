<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFetchAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fetch_attendances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('staff_central_id');
            $table->string('date_np');
            $table->date('date');
            $table->float('total_work_hour');
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
        Schema::dropIfExists('fetch_attendances');
    }
}
