<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'group_id', 'subject_id', 'teacher_id', 'room_id',
        'day_id', 'period_id', 'lesson_type', 'semester',
    ];

    public function group()   { return $this->belongsTo(Group::class); }
    public function subject() { return $this->belongsTo(Subject::class); }
    public function teacher() { return $this->belongsTo(User::class, 'teacher_id'); }
    public function room()    { return $this->belongsTo(Room::class); }
    public function day()     { return $this->belongsTo(Day::class); }
    public function period()  { return $this->belongsTo(Period::class); }
}