<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffFingerprintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_fingerprints', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('staff_central_id');
            $table->unsignedInteger('branch_id');
            $table->text('fingerprint')->nullable();
            $table->text('fingerprint_image')->nullable();
            $table->text('fingerprint2')->nullable();
            $table->text('fingerprint_image2')->nullable();
            $table->integer('status');
            $table->integer('sync');

            $table->timestamps();

            $table->foreign('staff_central_id')->references('id')->on('staff_main_mast');
            $table->foreign('branch_id')->references('office_id')->on('system_office_mast');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staff_fingerprints');
    }
}
