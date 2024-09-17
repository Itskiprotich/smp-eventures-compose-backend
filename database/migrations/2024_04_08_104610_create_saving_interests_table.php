<?php

use App\Models\Branch;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSavingInterestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saving_interests', function (Blueprint $table) {
            $table->id();
            $table->string('phone');
            $table->string('reference');
            $table->decimal('amount', 10, 2)->default(0.0);
            $table->decimal('available', 10, 2)->default(0.0);
            $table->string('year');
            $table->boolean('paid')->default(false);
            $table->foreignIdFor(Branch::class)->nullable()->default(1);
            $table->boolean('iswithdrawal')->default(false);
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
        Schema::dropIfExists('saving_interests');
    }
}
