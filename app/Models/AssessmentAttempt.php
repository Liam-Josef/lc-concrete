<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentAttempt extends Model
{
    protected $guarded = [];

    public function assessment() { return $this->belongsTo(Assessment::class); }
    public function student()    { return $this->belongsTo(Student::class); }
    public function answers()    { return $this->hasMany(AssessmentAttemptAnswer::class); }
}
