<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddColumnsToPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->integer('parent_id')->default(0);
            $table->integer('order')->default(0);
            $table->integer('isURL')->default(1);
            $table->string('icon')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn('parent_id');
            $table->dropColumn('order');
            $table->dropColumn('isURL');
            $table->dropColumn('icon');
        });
    }
}
