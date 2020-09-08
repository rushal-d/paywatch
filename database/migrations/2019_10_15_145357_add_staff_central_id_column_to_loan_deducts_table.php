<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStaffCentralIdColumnToLoanDeductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loan_deducts', function (Blueprint $table) {
            $table->unsignedBigInteger('staff_central_id')->nullable()->after('month_id');

            $table->foreign('staff_central_id')->references('id')->on('staff_main_mast');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loan_deducts', function (Blueprint $table) {
            $table->dropForeign(['staff_central_id']);
            $table->dropColumn('staff_central_id');
        });
    }
}
