<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{

  protected $fillable = [
    'fullname',
    'email',
    'username',
    'password',
    'role',
    'deleted',
  ];

  protected $hidden = [
    'password',
    'remember_token',
  ];

  protected $casts = [
    'email_verified_at' => 'datetime',
    // BỎ 'password' => 'hashed'
    'deleted' => 'boolean',
    'role' => 'integer',
  ];

  // protected static function booted()
  // {
  //   static::addGlobalScope('active', function ($builder) {
  //     $builder->where('deleted', false);
  //   });
  // }

  public static function withDeleted()
  {
    return static::withoutGlobalScope('active');
  }

  public function enrollments()
  {
    return $this->hasMany(Enrollment::class, 'user_id');
  }

  public function courses()
  {
    return $this->belongsToMany(Course::class, 'enrollments', 'user_id', 'course_id');
  }

  public function createdCourses()
  {
    return $this->hasMany(Course::class, 'instructor_id');
  }

  public function isAdmin()
  {
    return $this->role === 2;
  }

  public function isInstructor()
  {
    return $this->role === 1;
  }

  public function isStudent()
  {
    return $this->role === 0;
  }

  public function getRoleName()
  {
    return match ($this->role) {
      2 => 'Admin',
      1 => 'Instructor',
      0 => 'Student',
      default => 'Unknown',
    };
  }
}
