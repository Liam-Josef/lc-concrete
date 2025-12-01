<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected  $guarded = [];

    public function instructors() {
        return $this->belongsToMany(Instructor::class);
    }
    public function organization() { return $this->belongsTo(Organization::class, 'org_id'); }
    public function lessons()      {  return $this->hasMany(\App\Models\Lesson::class, 'course_id'); }

    public function videos()
    {
        return $this->hasManyThrough(
            \App\Models\Video::class,   // final
            \App\Models\Lesson::class,  // through
            'course_id',                // FK on lessons referencing courses.id
            'lesson_id',                // FK on videos referencing lessons.id
            'id',                       // local key on courses
            'id'                        // local key on lessons
        );
    }


}
