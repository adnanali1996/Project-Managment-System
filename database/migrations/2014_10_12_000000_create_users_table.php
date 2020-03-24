<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('admin')->default(0);
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role')->default('User');
            $table->rememberToken();
            $table->timestamps();
            $table->string('gcm_registration_id')->default(null);
            $table->integer('dept_id')->unsigned() ;
            $table->foreign('dept_id')->references('id')->on('projects');
        });   


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
