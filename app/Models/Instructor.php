<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Instructor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'bio',
        'banner_image'
    ];

    // Optional: Relationship to courses if an instructor has many courses
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class);
    }

    public function students()
    {
        return $this->hasManyThrough(
            Student::class, 
            Course::class,
            'teacher_id', // Foreign key on courses table
            'id',         // Foreign key on students/enrollments table
            'id',         // Local key on teachers table
            'student_id'  // Local key on courses/enrollments
        );
    }
}