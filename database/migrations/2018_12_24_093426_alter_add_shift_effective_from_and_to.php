<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddShiftEffectiveFromAndTo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staff_shift_histories', function (Blueprint $table) {
            $table->date('effective_from');
            $table->date('effective_to')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('staff_shift_histories', function (Blueprint $table) {
            $table->dropColumn('effective_from');
            $table->dropColumn('effective_to');
        });
    }
}
