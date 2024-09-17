<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerSavingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_savings', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 10, 2)->default(0.0);
            $table->string('phone');
            $table->string('name');
            $table->decimal('welfare', 10, 2)->default(0.0);
            $table->decimal('share_capital', 10, 2)->default(0.0);
            $table->decimal('reg_fee', 10, 2)->default(0.0);
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
        Schema::dropIfExists('customer_savings');
    }
}
