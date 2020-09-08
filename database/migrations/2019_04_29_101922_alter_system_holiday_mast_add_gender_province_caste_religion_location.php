<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSystemHolidayMastAddGenderProvinceCasteReligionLocation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('system_holiday_mast', function (Blueprint $table) {
            $table->integer('gender_id')->nullable();
            $table->unsignedInteger('caste_id')->nullable();
            $table->foreign('caste_id')->references('id')->on('castes');

            $table->unsignedInteger('province_id')->nullable();
            $table->foreign('province_id')->references('id')->on('provinces');

            $table->unsignedInteger('religion_id')->nullable();
            $table->foreign('religion_id')->references('id')->on('religions');

            $table->unsignedInteger('location_id')->nullable();
            $table->foreign('location_id')->references('id')->on('system_dist_mast');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('system_holiday_mast', function (Blueprint $table) {
            $table->dropColumn('gender_id');
            $table->dropForeign(['caste_id']);
            $table->dropColumn('caste_id');
            $table->dropForeign(['province_id']);
            $table->dropColumn('province_id');
            $table->dropForeign(['religion_id']);
            $table->dropColumn('religion_id');
            $table->dropForeign(['location_id']);
            $table->dropColumn('location_id');
        });
    }
}
