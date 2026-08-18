<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'course_category_id',
        'title',
        'hour',
        'price',
        'desc',
        'image',
    ];

    // Relationship to Course Category
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('order', 'asc');
    }
}