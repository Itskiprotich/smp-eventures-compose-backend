<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJournalEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->string('reference'); 
            $table->decimal('amount', 10, 2)->default(0.0); 
            $table->string('debit_account');
            $table->string('credit_account');
            $table->string('narration'); 
            $table->string('trans_date'); 
            $table->string('loan_type');
            $table->string('payment_ref');
            $table->decimal('running_balance')->default(0.0); 
            $table->string('name');
            $table->string('phone'); 
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
        Schema::dropIfExists('journal_entries');
    }
}
