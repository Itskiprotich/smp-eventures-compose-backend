<?php

use App\Models\Courses\Course;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseFaqsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_faqs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Course::class)->onDelete('cascade')->nullable();
            $table->string("title");         
            $table->text('answer')->nullable();
            $table->text('order')->nullable();
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
        Schema::dropIfExists('course_faqs');
    }
}
