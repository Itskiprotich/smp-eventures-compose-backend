<?php

use App\Models\BlogCategories;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();  
            $table->foreignIdFor(BlogCategories::class)->onDelete('cascade')->nullable();  
            $table->foreignIdFor(User::class)->onDelete('cascade')->nullable();   
            $table->string('title');
            $table->string('slug');
            $table->string('image');
            $table->text('description');
            $table->longText('content');
            $table->integer('visit_count')->unsigned()->nullable()->default(0);
            $table->boolean('enable_comment')->default(true);
            $table->enum('status', ['pending', 'publish'])->default('pending');
            $table->boolean('deleted')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blogs');
    }
}
