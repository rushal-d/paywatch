<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSundryTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sundry_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('staff_central_id')->unsigned();
	        $table->foreign('staff_central_id')->references('id')->on('staff_main_mast')->onDelete('cascade');
            $table->string('transaction_date', 20);
            $table->date('transaction_date_en');
	        $table->integer('transaction_type_id')->unsigned();
            $table->foreign('transaction_type_id')->references('id')->on('sundry_types')->onDelete('cascade');
            $table->tinyInteger('dr_installment')->nullable();
            $table->decimal('dr_amount',10,2)->nullable();
            $table->decimal('dr_balance',10,2)->nullable();
            $table->tinyInteger('cr_installment')->nullable();
	        $table->decimal('cr_amount',10,2)->nullable();
	        $table->decimal('cr_balance',10,2)->nullable();
	        $table->string('notes', 200)->nullable();
	        $table->integer('created_by')->nullable();
	        $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('sundry_transactions');
    }
}
