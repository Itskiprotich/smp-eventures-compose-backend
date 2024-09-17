<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenaltyTrackersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penalty_trackers', function (Blueprint $table) {
            $table->id();
            $table->string('loan_ref'); 
            $table->decimal('amount', 10, 2)->default(0.0);
            $table->decimal('balance', 10, 2)->default(0.0);
            $table->decimal('current', 10, 2)->default(0.0);
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
        Schema::dropIfExists('penalty_trackers');
    }
}
