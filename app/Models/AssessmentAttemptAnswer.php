<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentAttemptAnswer extends Model
{
    protected $guarded = [];

    public function attempt()  { return $this->belongsTo(AssessmentAttempt::class, 'assessment_attempt_id'); }
    public function question() { return $this->belongsTo(Question::class); }
}
