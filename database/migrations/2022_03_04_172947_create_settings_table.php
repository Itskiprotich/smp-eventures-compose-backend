<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('days');
            $table->string('admin_email');
            $table->string('code');
            $table->decimal('investor_rate', 10, 2)->default(0.0);
            $table->decimal('developer_rate', 10, 2)->default(0.0);
            $table->decimal('system_rate', 10, 2)->default(0.0);
            $table->decimal('saving_rate', 10, 2)->default(0.0);
            $table->boolean('status')->default(true);
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
        Schema::dropIfExists('settings');
    }
}