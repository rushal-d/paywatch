<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('shift_name');
            $table->unsignedInteger('branch_id');

            $table->time('punch_in');
            $table->double('punch_in_threshold')->default(0);
            $table->time('punch_out');
            $table->double('punch_out_threshold')->default(0);

            $table->time('min_tiffin_out')->nullable();
            $table->time('max_tiffin_in')->nullable();

            $table->double('tiffin_duration')->default(0);
            $table->double('tiffin_threshold')->default(0);

            $table->time('min_lunch_out')->nullable();
            $table->time('max_lunch_in')->nullable();

            $table->double('lunch_duration')->default(0);
            $table->double('lunch_threshold')->default(0);

            $table->double('personal_in_out_duration')->default(0);
            $table->double('personal_in_out_threshold')->default(0);

            $table->unsignedInteger('parent_id')->nullable();
            $table->boolean('active')->default(1);

            $table->foreign('branch_id')
                ->references('office_id')->on('system_office_mast')
                ->onDelete('cascade');
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
        Schema::dropIfExists('shifts');
    }
}
