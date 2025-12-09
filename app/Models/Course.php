<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model {
    protected $table = 'courses';
    protected $fillable = ['title', 'description', 'instructor_id', 'category_id', 'price', 'duration_weeks', 'level', 'image', 'created_at', 'deleted'];

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
