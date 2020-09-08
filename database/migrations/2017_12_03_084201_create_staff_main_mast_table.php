\<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffMainMastTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_main_mast', function (Blueprint $table) {
	        $table->bigIncrements('id');
	        $table->tinyInteger("staff_type")->nullable();
//            $table->string("staff_central_id",7)->nullable();
	        $table->string("branch_id",7)->nullable();
	        $table->string("image",225)->nullable();
	        $table->string("upload_file",225)->nullable();
	        $table->string("name_nep",100)->nullable();
	        $table->string("name_eng",100)->nullable();
	        $table->string("fname_nep",100)->nullable();
	        $table->string("FName_Eng",100)->nullable();
	        $table->string("gfname_nep",100)->nullable();
	        $table->string("gfname_eng",100)->nullable();
	        $table->string("spname_nep",100)->nullable();
	        $table->string("spname_eng",100)->nullable();
	        $table->integer("district_id")->unsigned();
	        $table->foreign('district_id')->references('id')->on('system_dist_mast')->onDelete('cascade');
	        $table->integer("ward_no")->nullable();
	        $table->string("tole_basti",100)->nullable();
	        $table->string("marrid_stat",1)->nullable();
	        $table->string("Gender",1)->nullable();
	        $table->integer("edu_id")->unsigned();
	        $table->foreign('edu_id')->references('edu_id')->on('system_edu_mast')->onDelete('cascade');
	        $table->date("date_birth")->nullable();
	        $table->string("date_birth_np", 15)->nullable();
	        $table->date("appo_date")->nullable();
	        $table->string("appo_date_np", 15)->nullable();
	        $table->integer("post_id")->unsigned();
	        $table->foreign('post_id')->references('post_id')->on('system_post_mast')->onDelete('cascade');
	        $table->integer("jobtype_id")->nullable();
	        $table->integer("appo_office")->nullable();
	        $table->string("staff_status",1)->nullable();
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
        Schema::dropIfExists('staff_main_mast');
    }
}
