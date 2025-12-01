<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected  $guarded = [];


    public function lessons() {
        return $this->belongsToMany(Lesson::class)
            ->withPivot(['complete', 'access_granted', 'access_granted_at'])
            ->withTimestamps();
    }
    public function videos() {
        return $this->belongsToMany(Video::class)->withPivot('completed','completed_at')->withTimestamps();
    }
    public function questions() {
        return $this->belongsToMany(Question::class)
            ->withPivot('answer', 'is_correct', 'student_complete')
            ->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // app/Models/Student.php
    public function invoices()  { return $this->hasMany(\App\Models\Invoice::class); }
    public function payments()  { return $this->hasManyThrough(\App\Models\Payment::class, \App\Models\Invoice::class); }
    public function certificates()
    {
        return $this->hasMany(\App\Models\Certificate::class);
    }

// convenience
    public function certificateForLesson(int $lessonId)
    {
        return $this->certificates()->where('lesson_id', $lessonId)->first();
    }


}
