<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repayments', function (Blueprint $table) {
            $table->id();  
            $table->string('phone');
            $table->string('loan_ref');
            $table->string('date_paid');
            $table->string('initiator');
            $table->string('reference');
            $table->decimal('paid_amount', 10, 2)->default(0.0);
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
        Schema::dropIfExists('repayments');
    }
}
