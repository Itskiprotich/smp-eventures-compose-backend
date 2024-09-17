<?php

use App\Models\CourseDiscounts;
use App\Models\Courses\Course;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountedCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounted_courses', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Course::class)->onDelete('cascade')->nullable();
            $table->foreignIdFor(CourseDiscounts::class)->onDelete('cascade')->nullable();
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
        Schema::dropIfExists('discounted_courses');
    }
}
