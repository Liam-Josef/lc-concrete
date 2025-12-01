<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected  $guarded = [];

    public function lessons() {
        return $this->belongsToMany(Lesson::class)->withPivot('section_number')->withTimestamps();
    }
    public function videos() {
        return $this->belongsToMany(Video::class);
    }

    public function instructors()
    {
        return $this->belongsToMany(Instructor::class);
    }

}
