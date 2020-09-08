<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffPaymentMastTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('staff_payment_mast', function (Blueprint $table) {
			$table->increments('id');
			$table->bigInteger('staff_central_id');
			$table->integer("pay_type")->nullable();
			$table->string("account")->nullable();
			$table->integer("dear_allow")->nullable();
			$table->integer("risk_allow")->nullable();
			$table->integer("extra_allow")->nullable();
			$table->integer("other_allow")->nullable();
			$table->integer("dasai_allow")->nullable();
			$table->integer("special_allow")->nullable();
			$table->integer("gratu_mode")->nullable();
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('staff_payment_mast');
	}
}