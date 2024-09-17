<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parameters', function (Blueprint $table) {
            $table->id();
            $table->string('prefix'); 
            $table->string('serfix'); 
            $table->string('grace_period'); 
            $table->decimal('reg_fee', 10, 2)->default(0.0);
            $table->boolean('admin_fee')->default(true);
            $table->boolean('reminders')->default(true);
            $table->boolean('rollover')->default(true);
            $table->boolean('active')->default(true);
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
        Schema::dropIfExists('parameters');
    }
}
