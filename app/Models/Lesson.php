<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lesson extends Model
{
    use HasFactory;

    protected $table = 'lessons';
    
    // BẮT BUỘC: Vì bảng chỉ có created_at, không có updated_at
    public $timestamps = false; 

    // Các trường có thể gán giá trị hàng loạt (Mass Assignable)
    protected $fillable = [
        'course_id',
        'title',
        'content',
        'video_url',
        'order',   
        'created_at',
        'is_deleted'
    ];

    // Mối quan hệ 1: Lesson thuộc về Course
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
    
    // // Mối quan hệ 2: Lesson có nhiều Material (BẮT BUỘC cho logic Controller)
    public function materials()
    {
        return $this->hasMany(Material::class, 'lesson_id');
    }
}