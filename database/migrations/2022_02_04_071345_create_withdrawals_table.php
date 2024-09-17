<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->string('phone');
            $table->string('reference');
            $table->string('action_by');
            $table->string('approved_by')->nullable();
            $table->decimal('amount', 10, 2)->default(0.0);
            $table->decimal('balance', 10, 2)->default(0.0);
            $table->string('narration')->nullable();
            $table->boolean('mode')->default(false);
            $table->boolean('status')->default(false);
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
        Schema::dropIfExists('withdrawals');
    }
}
