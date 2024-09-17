<?php

use App\Models\Courses\Course;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeneralCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('general_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Student::class)->onDelete('cascade')->nullable();
            $table->foreignIdFor(Course::class)->onDelete('cascade')->nullable();
            $table->foreignIdFor(User::class)->onDelete('cascade')->nullable();
            $table->text('comment')->nullable();
            $table->string('type')->default('Main Comment');
            $table->string('status')->default('Pending');
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
        Schema::dropIfExists('general_comments');
    }
}
