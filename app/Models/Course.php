<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Course extends Model
{
  use HasFactory;
  protected $table = 'courses';
  protected $fillable = ['title', 'description', 'instructor_id', 'category_id', 'price', 'duration_weeks', 'level', 'image', 'created_at', 'is_deleted'];

  public function instructor()
  {
    return $this->belongsTo(User::class, 'instructor_id');
  }

  public function category()
  {
    return $this->belongsTo(Category::class, 'category_id');
  }

  public function lessons()
  {
    return $this->hasMany(Lesson::class, 'course_id');
  }

  public function enrollments()
  {
    return $this->hasMany(Enrollment::class, 'course_id');
  }
}
