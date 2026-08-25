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
    ];

    // Optional: Relationship to courses if an instructor has many courses
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class);
    }
}