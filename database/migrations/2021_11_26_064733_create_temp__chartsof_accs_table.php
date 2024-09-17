<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTempChartsofAccsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temp__chartsof_accs', function (Blueprint $table) {
            $table->id();
            $table->string('account_type');
            $table->string('account_code');
            $table->string('account_name');
            $table->decimal('amount_cr', 10, 2);
            $table->decimal('amount_dr', 10, 2);
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
        Schema::dropIfExists('temp__chartsof_accs');
    }
}
