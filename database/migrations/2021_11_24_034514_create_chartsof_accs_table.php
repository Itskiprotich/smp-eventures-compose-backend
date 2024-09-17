<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChartsofAccsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chartsof_accs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Product::class); 
            $table->foreignIdFor(Category::class); 
            $table->foreignIdFor(SubCategory::class); 
            $table->string('account_no');
            $table->string('chart_name');
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
        Schema::dropIfExists('chartsof_accs');
    }
}
