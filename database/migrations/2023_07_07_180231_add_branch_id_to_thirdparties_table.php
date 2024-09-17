<?php

use App\Models\Branch;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBranchIdToThirdpartiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('thirdparties', function (Blueprint $table) {
            //
            $table->foreignIdFor(Branch::class)
            ->nullable()
            ->default(1)
            ->onDelete('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('thirdparties', function (Blueprint $table) {
            //
            // $table->dropForeign(['branch_id']);
            // $table->dropColumn('branch_id');
        });
    }
}
