<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('firstname');
            $table->string('lastname'); 
            $table->string('phone');
            $table->string('physical_address')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('devicename');
            $table->string('device_id');
            $table->string('code')->nullable();
            $table->string('email')->unique();
            $table->string('membership_no')->nullable();
            $table->string('photo')->nullable();
            $table->string('password');
            $table->string('type');
            $table->string('national_id')->nullable();
            $table->string('gender')->nullable();
            $table->string('id_front')->nullable();
            $table->string('id_back')->nullable();
            $table->string('dob')->nullable();
            $table->decimal('loanlimit', 10, 2)->default(0.0);
            $table->string('action_by')->nullable();
            $table->string('approved_by')->nullable();
            $table->boolean('automatic')->default(false);
            $table->string('otp')->nullable();
            $table->string('status')->default('Pending');
            $table->boolean('blacklist')->default(false);
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
        Schema::dropIfExists('customers');
    }
}
