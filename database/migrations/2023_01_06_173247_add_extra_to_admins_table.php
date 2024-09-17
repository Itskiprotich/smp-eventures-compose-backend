<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraToAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admins', function (Blueprint $table) {
            //
            $table->string('role_name')->default('teacher');
            $table->boolean('offline')->default(false);
            $table->text('offline_message')->nullable();
            $table->decimal('rate', 10, 2)->default(0.0);
            $table->text('avatar')->nullable();
            $table->boolean('meeting_status')->default(false);
            $table->boolean('verified')->default(false);
            $table->text('address')->nullable();
            $table->text('bio')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admins', function (Blueprint $table) {
            //
            $table->dropColumn('role_name');
        });
    }
}
