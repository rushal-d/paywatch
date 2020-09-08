<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceLogsTable extends Migration
{
    public function up()
    {
        Schema::create('attendance_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('staff_central_id');
            $table->bigInteger('main_id');
            $table->datetime('punchin_datetime')->nullable();
            $table->string('punchin_datetime_np')->nullable();
            $table->tinyInteger('sync')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendance_logs');
    }
}
