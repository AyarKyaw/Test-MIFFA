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
}