<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSyncRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sync_records', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('sync_time')->nullable();
            $table->integer('branch_id')->nullable();
            $table->boolean('alert_email_sent')->default(0);
            $table->boolean('alert_sms_sent')->default(0);
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
        Schema::dropIfExists('sync_records');
    }
}
