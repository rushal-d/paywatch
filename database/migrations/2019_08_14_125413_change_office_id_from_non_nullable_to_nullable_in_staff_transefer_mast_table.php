<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeOfficeIdFromNonNullableToNullableInStaffTranseferMastTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staff_transefer_mast', function (Blueprint $table) {
            $table->unsignedInteger('office_id')->nullable(1)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('staff_transefer_mast', function (Blueprint $table) {
            $table->unsignedInteger('office_id')->nullable(1)->change();

        });
    }
}
