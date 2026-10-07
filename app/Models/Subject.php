<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['code', 'name', 'credits'];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}