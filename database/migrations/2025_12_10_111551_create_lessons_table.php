<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('lessons', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('course_id');
        $table->string('title');
        $table->longText('content');
        $table->string('video_url')->nullable();
        $table->integer('order');
        $table->timestamp('created_at')->nullable();
        $table->tinyInteger('is_deleted')->default(0);
        $table->foreign('course_id')->references('id')->on('courses');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
