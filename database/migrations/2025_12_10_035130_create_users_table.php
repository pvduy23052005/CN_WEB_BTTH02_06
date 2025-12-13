<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('users', function (Blueprint $table) {
      // Khóa chính: id (INTEGER, Primary Key)
      $table->id('id'); // Tương đương $table->bigIncrements('id');

      // Các trường VARCHAR(255)
      $table->string('username', 255)->unique(); // Giả sử là unique
      $table->string('email', 255)->unique(); // Giả sử là unique
      $table->string('password', 255);
      $table->string('fullname', 255)->nullable(); // Giả sử có thể null

      $table->integer('role')->default(0); // Giả sử có giá trị mặc định

      $table->boolean('deleted')->default(0);

      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('users');
  }
};
