<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTransBankStatement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trans_bank_statement', function (Blueprint $table) {
            $table->integer('payroll_id')->unsigned();
            $table->foreign('payroll_id')
                ->references('id')->on('payroll_details')
                ->onDelete('cascade');
            $table->integer('branch_id');
            $table->integer('bank_id');
            $table->string('acc_no');
            $table->string('brcode');
            $table->string('trans_type');
            $table->double('total_payment');
            $table->text('remarks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trans_bank_statement', function (Blueprint $table) {
            $table->dropForeign(['payroll_id']);
            $table->dropColumn('payroll_id');
            $table->dropColumn('branch_id');
            $table->dropColumn('bank_id');
            $table->dropColumn('acc_no');
            $table->dropColumn('brcode');
            $table->dropColumn('trans_type');
            $table->dropColumn('total_payment');
            $table->dropColumn('remarks');
        });
    }
}
