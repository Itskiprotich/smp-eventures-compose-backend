<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLockBuysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lock_buys', function (Blueprint $table) {
            $table->id();
            $table->string('phone');
            $table->string('owner');
            $table->string('reference');
            $table->decimal('amount', 10, 2)->default(0.0);
            $table->decimal('balance', 10, 2)->default(0.0);
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
        Schema::dropIfExists('lock_buys');
    }
}
