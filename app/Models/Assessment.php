<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    protected $guarded = [];

    public function lesson()      { return $this->belongsTo(Lesson::class); }
    public function questions()   { return $this->belongsToMany(Question::class)->withPivot('position')->withTimestamps()->orderBy('assessment_question.position'); }
    public function attempts()    { return $this->hasMany(AssessmentAttempt::class); }

    public function scopePre($q)  { return $q->where('type','pre'); }
    public function scopePost($q) { return $q->where('type','post'); }
}
