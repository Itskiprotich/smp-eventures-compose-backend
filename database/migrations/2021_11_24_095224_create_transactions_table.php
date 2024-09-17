<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->decimal('amount', 10, 2);
            $table->string('phone');
            $table->string('loan_ref');
            $table->string('response')->nullable();
            $table->string('callback_response')->nullable();
            $table->boolean('status')->default(true);
            $table->string('trans_id');
            $table->string('trans_ref')->nullable();
            $table->string('result_code')->nullable();
             
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
