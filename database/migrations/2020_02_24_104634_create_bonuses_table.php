<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBonusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bonuses', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('fiscal_year_id');
            $table->foreign('fiscal_year_id')->references('id')->on('fiscal_year');
            $table->unsignedBigInteger('staff_central_id');
            $table->foreign('staff_central_id')->references('id')->on('staff_main_mast');
            $table->unsignedInteger('branch_id');
            $table->foreign('branch_id')->references('office_id')->on('system_office_mast');
            $table->date('received_date');
            $table->string('received_date_np');
            $table->double('received_amount', 12, 2);
            $table->unsignedInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users');
            $table->unsignedInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->unsignedInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users');
            $table->softDeletes();
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
        Schema::dropIfExists('bonuses');
    }
}
