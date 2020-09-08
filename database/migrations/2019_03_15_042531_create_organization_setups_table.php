<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationSetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_setups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('organization_name')->nullable();
            $table->string('organization_address')->nullable();
            $table->string('organization_contact')->nullable();
            $table->string('organization_website')->nullable();
            $table->string('organization_email')->nullable();
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
        Schema::dropIfExists('organization_setups');
    }
}
