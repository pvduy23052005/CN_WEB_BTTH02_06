<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Material extends Model
{
    use HasFactory;

    protected $table = 'materials';

    // BẮT BUỘC: Vì Migration không có updated_at (chỉ có uploaded_at)
    public $timestamps = false; 
    
    // Các trường có thể gán giá trị hàng loạt
    protected $fillable = [
        'lesson_id', 
        'filename', 
        'file_path', 
        'file_type',
        'uploaded_at', // Cần liệt kê nếu muốn gán giá trị thủ công
    ];
  
    /**
     * Mối quan hệ: Một Material thuộc về một Lesson.
     */
    public function lesson()
    {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }
   
}