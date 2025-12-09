<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
  use HasFactory, Notifiable;

  protected $table = 'users';

  protected $fillable = [
    'username',
    'email',
    'password',
    'fullname',
    'role',
    'created_at',
    'deleted'
  ];

  protected $hidden = [
    'password',
  ];


  public function courses()
  {
    return $this->hasMany(Course::class, 'instructor_id');
  }

  public function enrollments()
  {
    return $this->hasMany(Enrollment::class, 'user_id');
  }
}
