<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFloatStatementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('float_statements', function (Blueprint $table) {
            $table->id();
            $table->string('phone');
            $table->string('reference');
            $table->string('description');
            $table->decimal('amount', 10, 2)->default(0.0);
            $table->decimal('total', 10, 2)->default(0.0);
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
        Schema::dropIfExists('float_statements');
    }
}
