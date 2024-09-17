
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfitSharesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profit_shares', function (Blueprint $table) {
            $table->id();
            $table->string('year');
            $table->string('month');
            $table->string('phone');
            $table->string('name');
            $table->decimal('float_balance', 10, 2)->default(0.0);
            $table->decimal('ratio', 10, 2)->default(0.0);
            $table->decimal('earnings', 10, 2)->default(0.0);
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
        Schema::dropIfExists('profit_shares');
    }
}