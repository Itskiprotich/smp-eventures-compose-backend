<?php

use App\Models\CourseCategory;
use App\Models\CourseType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->unsignedBigInteger('creator_user_id')->nullable();
            $table->foreignIdFor(CourseType::class)->onDelete('cascade')->nullable();
            $table->foreignIdFor(CourseCategory::class)->onDelete('cascade')->nullable();
            $table->string('title', 64);
            $table->timestamp('start_date');
            $table->string('end_date');
            $table->string('image_cover');
            $table->string('video_demo')->nullable();
            $table->integer('capacity')->unsigned();
            $table->integer('price')->unsigned();
            $table->text('description')->nullable();
            $table->boolean('support')->default(false);
            $table->boolean('partner_instructor')->default(false);
            $table->boolean('subscribe')->default(false);
            $table->text('message_for_reviewer')->nullable();
            $table->enum('status', ['active', 'pending', 'is_draft', 'inactive']);
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('creator_user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('courses');
    }
}
