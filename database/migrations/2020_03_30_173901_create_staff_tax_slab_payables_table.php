<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffTaxSlabPayablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_tax_slab_payables', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('staff_central_id');
            $table->foreign('staff_central_id')->references('id')->on('staff_main_mast');

            $table->unsignedInteger('fiscal_year_id');
            $table->foreign('fiscal_year_id')->references('id')->on('fiscal_year');

            $table->unsignedInteger('tds_detail_id');
            $table->foreign('tds_detail_id')->references('id')->on('system_tdsdetails_mast');

            $table->double('tax_amount_yearly', 12, 3)->default(0);
            $table->double('tax_amount_monthly', 12, 3)->default(0);

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
        Schema::dropIfExists('staff_tax_slab_payables');
    }
}
