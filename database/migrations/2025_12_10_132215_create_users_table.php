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
        $table->id(); // Tự động là INT, PRIMARY KEY, AUTO_INCREMENT
        
        $table->string('username')->unique(); // unique để không trùng tên đăng nhập
        $table->string('email')->unique();
        $table->string('password');
        $table->string('fullname');
        
        // 0: học viên, 1: giảng viên, 2: quản trị viên. Mặc định là 0.
        $table->integer('role')->default(0); 
        
        $table->timestamps(); // Tạo 2 cột: created_at và updated_at
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
