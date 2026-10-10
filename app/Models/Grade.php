<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = [
        'student_id',
        'subject_id',
        'schedule_id',
        'date',
        'lesson_score',
        'homework_score',
        'comment',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}