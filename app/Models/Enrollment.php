<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Enrollment extends Model
{
    use HasFactory;
    
    protected $table = 'enrollments';
    public $timestamps = false; 

    protected $fillable = [
        'student_id',     
        'course_id',
        'enrolled_date',
        'status', 
        'progress', 
    ];
    
    protected $casts = [
        'enrolled_date' => 'datetime',
        'progress' => 'integer',
    ];

    // Mối quan hệ: Dùng student_id làm khóa ngoại
    public function user()
    {
        return $this->belongsTo(User::class, 'student_id'); 
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
    public function student()
{
    // Liên kết khóa ngoại student_id với bảng users
    return $this->belongsTo(User::class, 'student_id');
}
}