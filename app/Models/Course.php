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