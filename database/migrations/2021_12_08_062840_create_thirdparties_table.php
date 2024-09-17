<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThirdpartiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('thirdparties', function (Blueprint $table) {
            $table->id();
            $table->string('email_address');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('phone');
            $table->string('kra_pin')->nullable();
            $table->string('id_number')->nullable();
            $table->string('nationality')->nullable();
            $table->string('income')->nullable();
            $table->string('activity')->nullable();
            $table->string('username')->nullable();
            $table->string('workplace')->nullable();
            $table->string('dob')->nullable();
            $table->string('gender')->nullable();
            $table->string('code')->nullable();
            $table->string('town')->nullable(); 
            $table->boolean('status')->default(true);
            $table->decimal('float_balance', 10, 2)->default(0.0);
            $table->decimal('interest_balance', 10, 2)->default(0.0);
            $table->decimal('pending_pool', 10, 2)->default(0.0);
            $table->decimal('approved_pool', 10, 2)->default(0.0);
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
        Schema::dropIfExists('thirdparties');
    }
}
