<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = ['student_id','lesson_id','serial','issued_at'];
    protected $casts = ['issued_at' => 'datetime'];

    public function lesson()  { return $this->belongsTo(Lesson::class); }
    public function student() { return $this->belongsTo(Student::class); }
}
