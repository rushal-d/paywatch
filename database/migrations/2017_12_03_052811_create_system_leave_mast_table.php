<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemLeaveMastTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_leave_mast', function (Blueprint $table) {
            $table->increments('leave_id');
            $table->string("leave_name", 50)->nullable();
            $table->string("leave_code", 2)->unique()->nullable();
            $table->integer("max_days")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_leave_mast');
    }
}
