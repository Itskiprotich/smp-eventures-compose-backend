<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('password');
            $table->string('otp')->nullable();
            $table->string('phone');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('usertype');
            $table->string('modules')->nullable();
            $table->string('first_login')->default(true);
            $table->string('ot_time')->nullable();
            $table->string('permissions')->nullable();
            $table->boolean('status')->default(true);
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
        Schema::dropIfExists('admins');
    }
}
