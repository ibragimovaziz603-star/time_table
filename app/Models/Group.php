<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = ['name', 'faculty', 'course', 'starosta_id'];

    public function students()
    {
        return $this->belongsToMany(User::class, 'student_group', 'group_id', 'student_id');
    }

    public function starosta()
    {
        return $this->belongsTo(User::class, 'starosta_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function getCourseFromNameAttribute(): ?int
    {
        if (preg_match('/-(\d{2})$/', $this->name, $matches)) {
            $year = (int) $matches[1];

            return match ($year) {
                26 => 1,
                25 => 2,
                24 => 3,
                23 => 4,
                default => null,
            };
        }

        return null;
    }

    public function syncCourseFromName(): void
    {
        $this->course = $this->course_from_name;
        $this->save();
    }
}