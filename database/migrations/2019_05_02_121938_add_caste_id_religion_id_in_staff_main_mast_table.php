<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCasteIdReligionIdInStaffMainMastTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staff_main_mast', function (Blueprint $table) {
            $table->unsignedInteger('caste_id')->nullable()->after('edu_id');
            $table->foreign('caste_id')->references('id')->on('castes');

            $table->unsignedInteger('religion_id')->nullable()->after('edu_id');
            $table->foreign('religion_id')->references('id')->on('religions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('staff_main_mast', function (Blueprint $table) {
            $table->dropForeign(['caste_id']);
            $table->dropColumn('caste_id');

            $table->dropForeign(['religion_id']);
            $table->dropColumn('religion_id');
        });
    }
}
