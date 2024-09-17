<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->string('phone');
            $table->string('loan_code');
            $table->string('loan_ref');
            $table->string('disbursment_date')->nullable();
            $table->decimal('rate_applied', 10, 2)->default(0.0);
            $table->decimal('loan_disbursed', 10, 2)->default(0.0);
            $table->decimal('loan_amount', 10, 2)->default(0.0);
            $table->decimal('loan_balance', 10, 2)->default(0.0);
            $table->decimal('interest', 10, 2)->default(0.0);
            $table->decimal('admin_fee', 10, 2)->default(0.0);
            $table->decimal('principle', 10, 2)->default(0.0);
            $table->boolean('automatic')->default(false);
            $table->boolean('repayment_status')->default(false);
            $table->string('loan_status')->default('pending');
            $table->string('repayment_period')->nullable();
            $table->string('repayment_date')->nullable();
            $table->string('penalty_date')->nullable();
            $table->decimal('penalty_amount', 10, 2)->default(0.0);
            $table->string('clear_date')->nullable();
            $table->string('approved_by')->nullable();
            $table->string('actioned_by')->nullable();
            $table->string('customer_name')->nullable();
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
        Schema::dropIfExists('loans');
    }
}
