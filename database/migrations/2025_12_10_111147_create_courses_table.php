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
    Schema::create('courses', function (Blueprint $table) {

      $table->id();
      $table->string('title');
      $table->text('description');
      $table->unsignedBigInteger('instructor_id');
      $table->unsignedBigInteger('category_id');
      $table->decimal('price', 10, 2);
      $table->integer('duration_weeks');
      $table->string('level', 50);
      $table->string('image')->nullable();
      $table->timestamp('created_at')->nullable();
      $table->timestamp('updated_at')->nullable();
      $table->integer('is_deleted')->default(0);
      $table->boolean('is_active')->default(0);
      $table->foreign('instructor_id')->references('id')->on('users');
      $table->foreign('category_id')->references('id')->on('categories');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('courses');
  }
};
