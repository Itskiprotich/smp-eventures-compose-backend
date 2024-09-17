<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOverpaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('overpayments', function (Blueprint $table) {
            $table->id();
            $table->string('phone');
            $table->string('loan_ref');
            $table->decimal('amount', 10, 2)->default(0.0);
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
        Schema::dropIfExists('overpayments');
    }
}
