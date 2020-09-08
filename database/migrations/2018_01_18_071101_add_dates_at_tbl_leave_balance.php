<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDatesAtTblLeaveBalance extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('leave_balance', function (Blueprint $table) {
			$table->date('date');
			$table->string('date_np');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('leave_balance', function (Blueprint $table) {
			$table->dropColumn('date');
			$table->dropColumn('date_np');
		});
	}
}
