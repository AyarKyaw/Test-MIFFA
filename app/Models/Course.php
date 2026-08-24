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

    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    public function lessons()
    {
        // Traverses Course -> Unit -> Section -> Lesson
        return $this->hasManyThrough(
            Lesson::class,
            Section::class,
            'unit_id',    // Foreign key on sections table (via unit)
            'section_id'  // Foreign key on lessons table
        );
    }
    public function getProgressForUser($userId = null): int
    {
        $userId = $userId ?? auth()->id();
        if (!$userId) return 0;

        $totalLessons = $this->lessons()->count();
        if ($totalLessons === 0) return 0;

        $completedCount = $this->lessons()
            ->whereHas('users', function ($query) use ($userId) {
                $query->where('user_id', $userId)->where('is_completed', true);
            })->count();

        return (int) round(($completedCount / $totalLessons) * 100);
    }
}