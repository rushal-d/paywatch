<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterShiftAddBeforeAfterThreshold extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shifts', function (Blueprint $table) {
            $table->dropColumn('punch_in_threshold');
            $table->dropColumn('punch_out_threshold');
            $table->dropColumn('tiffin_threshold');
            $table->dropColumn('lunch_threshold');
//            $table->dropColumn('personal_in_out_threshold');


            $table->double('before_punch_in_threshold')->after('punch_in')->default(0);
            $table->double('after_punch_in_threshold')->after('before_punch_in_threshold')->default(0);

            $table->double('before_punch_out_threshold')->after('punch_out')->default(0);
            $table->double('after_punch_out_threshold')->after('before_punch_out_threshold')->default(0);

            $table->double('before_tiffin_threshold')->after('tiffin_duration')->default(0);
            $table->double('after_tiffin_threshold')->after('before_tiffin_threshold')->default(0);

            $table->double('before_lunch_threshold')->after('lunch_duration')->default(0);
            $table->double('after_lunch_threshold')->after('before_lunch_threshold')->default(0);

            /*$table->double('before_personal_in_out_threshold')->after('personal_in_out_duration')->default(0);
            $table->double('after_personal_in_out_threshold')->after('before_personal_in_out_threshold')->default(0);*/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shifts', function (Blueprint $table) {
            $table->double('punch_in_threshold')->default(0);
            $table->double('punch_out_threshold')->default(0);
            $table->double('tiffin_threshold')->default(0);
            $table->double('lunch_threshold')->default(0);
//            $table->double('personal_in_out_threshold')->default(0);

            $table->dropColumn('before_punch_in_threshold');
            $table->dropColumn('after_punch_in_threshold');

            $table->dropColumn('before_punch_out_threshold');
            $table->dropColumn('after_punch_out_threshold');

            $table->dropColumn('before_tiffin_threshold');
            $table->dropColumn('after_tiffin_threshold');

            $table->dropColumn('before_lunch_threshold');
            $table->dropColumn('after_lunch_threshold');

            /*$table->dropColumn('before_personal_in_out_threshold');
            $table->dropColumn('after_personal_in_out_threshold');*/
        });
    }
}
