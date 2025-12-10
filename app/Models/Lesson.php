<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $table = 'lessons';
    
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'content',
        'order',
        'created_at',
        'updated_at'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
