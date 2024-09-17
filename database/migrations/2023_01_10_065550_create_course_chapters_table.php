<?php

use App\Models\Courses\Course;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseChaptersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_chapters', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Course::class)->onDelete('cascade')->nullable();
            $table->string("title");        
            $table->boolean('check_all_contents_pass')->default(false);
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
        Schema::dropIfExists('course_chapters');
    }
}
