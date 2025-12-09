<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = ['name', 'description', 'created_at', 'deleted'];

    public function courses()
    {
        return $this->hasMany(Course::class, 'category_id');
    }
}
