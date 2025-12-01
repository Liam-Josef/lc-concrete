<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected  $guarded = [];

    public function contacts() {
        return $this->belongsToMany(Contact::class);
    }
    public function instructors() {
        return $this->belongsToMany(Instructor::class);
    }
    public function lessons() {
        return $this->hasMany(Lesson::class, 'org_id');
    }
    public function videos() {
        return $this->hasManyThrough(Video::class, Lesson::class, 'org_id', 'lesson_id');
    }
    public function courses() {
        return $this->belongsToMany(Course::class);
    }

}
