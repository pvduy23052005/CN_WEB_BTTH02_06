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
    Schema::create('courses', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('description'); // Dùng text vì mô tả thường dài
        
        // Khóa ngoại liên kết bảng users (giảng viên)
        // constrained('users') nghĩa là liên kết với id của bảng users
        $table->foreignId('instructor_id')->constrained('users')->onDelete('cascade');
        
        // Khóa ngoại liên kết bảng categories
        // LƯU Ý: Phải có bảng 'categories' trước thì dòng này mới chạy được
        $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
        
        // DECIMAL(10, 2) cho giá tiền
        $table->decimal('price', 10, 2);
        
        $table->integer('duration_weeks');
        
        // Level: Beginner, Intermediate, Advanced
        $table->string('level');
        
        // Ảnh có thể để trống (nullable) phòng trường hợp chưa có ảnh
        $table->string('image')->nullable();
        
        $table->timestamps(); // Tạo created_at và updated_at
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
