<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideoCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video_courses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('video_title')->collation('utf8mb4_unicode_ci');
            $table->string('video_file')->collation('utf8mb4_unicode_ci');
            $table->text('video_description')->collation('utf8mb4_unicode_ci')->nullable();
            $table->integer('uploaded_by')->unsigned();
            $table->string('visible_groups')->collation('utf8mb4_unicode_ci')->nullable();
            $table->string('visible_users')->collation('utf8mb4_unicode_ci')->nullable();
            $table->integer('views')->unsigned();
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
        Schema::dropIfExists('video_courses');
    }
}
