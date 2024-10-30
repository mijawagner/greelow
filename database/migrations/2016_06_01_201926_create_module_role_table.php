<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModuleRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module_role', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('module_id')->unsigned()->index()->comment('id of module');    // Foreign Key
            $table->integer('role_id')->unsigned()->index()->comment('id of role');    // Foreign Key
            $table->timestamps();
            $table->softDeletes();

            // Foreign Keys
            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('module_role');
    }
}
