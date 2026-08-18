<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'question_text',
        'type', // 'multiple_choice' or 'boolean'
        'order',
    ];

    /**
     * Get the lesson that owns the question.
     */
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Get all options for this question.
     */
    public function options()
    {
        return $this->hasMany(Option::class);
    }
}