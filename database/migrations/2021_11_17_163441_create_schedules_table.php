<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string('phone');
            $table->string('loan_ref');
            $table->string('due_date');
            $table->decimal('amount', 10, 2)->default(0.0);
            $table->decimal('paid_amount', 10, 2)->default(0.0);
            $table->decimal('balance', 10, 2)->default(0.0); 
            $table->string('status')->default('unpaid');
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
        Schema::dropIfExists('schedules');
    }
}
