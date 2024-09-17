<?php

use App\Models\Blogs;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogsCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogs_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Blogs::class)->onDelete('cascade')->nullable();  
            $table->integer('user_id')->unsigned();
            $table->integer('reply_id')->unsigned()->nullable();
            $table->text('comment')->nullable();
            $table->enum('status',['pending', 'active']);
            $table->boolean('backend')->default(false);
            $table->boolean('deleted')->default(false);
            $table->softDeletes();
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
        Schema::dropIfExists('blogs_comments');
    }
}
