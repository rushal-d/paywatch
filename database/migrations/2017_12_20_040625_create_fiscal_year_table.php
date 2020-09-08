<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFiscalYearTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fiscal_year', function (Blueprint $table) {
            $table->increments('id');
            $table->string("fiscal_start_date")->nullable();
            $table->string("fiscal_end_date")->nullable();
            $table->string("fiscal_start_date_np")->nullable();
            $table->string("fiscal_end_date_np")->nullable();
            $table->string("fiscal_code")->unique()->nullable();
            $table->string("fiscal_status",1)->nullable();
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
        Schema::dropIfExists('fiscal_year');
    }
}
