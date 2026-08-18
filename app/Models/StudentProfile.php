<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'gender',
        'membership_status',
        'nrc_number',
        'company',
        'job_title',
        'passport_photo',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}