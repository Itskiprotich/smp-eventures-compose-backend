<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSavingsProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('savings_products', function (Blueprint $table) {
            $table->id();
            $table->string('product_code');
            $table->string('product_name');
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
        Schema::dropIfExists('savings_products');
    }
}
