<?php

use App\Models\Courses\Course;
use App\Models\Student;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Student::class)->onDelete('cascade')->nullable();
            $table->foreignIdFor(Course::class)->onDelete('cascade')->nullable();
            $table->string('description')->nullable();
            $table->string('reason')->nullable();
            $table->decimal('content_quality', 10, 2)->default(0.0); 
            $table->decimal('instructor_skills', 10, 2)->default(0.0); 
            $table->decimal('purchase_worth', 10, 2)->default(0.0); 
            $table->decimal('support_quality', 10, 2)->default(0.0); 
            $table->boolean('approved')->default(false);
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
        Schema::dropIfExists('course_reviews');
    }
}
