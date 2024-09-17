<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_types', function (Blueprint $table) {
            $table->id();
            $table->string('loan_code');
            $table->string('loan_name');
            $table->string('duration');
            $table->decimal('min_limit', 10, 2)->default(0.0);
            $table->decimal('max_limit', 10, 2)->default(0.0);
            $table->decimal('interest_rate', 10, 2)->default(0.0);
            $table->decimal('admin_fee', 10, 2)->default(0.0);
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
        Schema::dropIfExists('loan_types');
    }
}
