<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = ['course_id', 'title', 'type', 'video_url', 'content', 'order'];

    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
