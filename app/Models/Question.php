<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected  $guarded = [];

    public function videos() {
        return $this->belongsToMany(Video::class);
    }
    public function students() {
        return $this->belongsToMany(Student::class)
            ->withPivot('answer', 'is_correct', 'student_complete')
            ->withTimestamps();
    }

    public function options(): array
    {
        // return only filled answers in order
        $opts = [];
        for ($i=1; $i<=4; $i++) {
            $val = $this->{"answer_{$i}"} ?? null;
            if ($val || $i <= 2) { // at least first two always exist (T/F or MC min 2)
                $opts[$i] = $val ?: ($i === 1 ? 'True' : ($i === 2 ? 'False' : null));
            }
        }
        return array_filter($opts, fn($v)=>!is_null($v));
    }

    public function isOptionCorrect(int $i): bool
    {
        return (bool) ($this->{"answer_{$i}_correct"} ?? false);
    }

    protected $casts = [
        'answer_1_correct' => 'bool',
        'answer_2_correct' => 'bool',
        'answer_3_correct' => 'bool',
        'answer_4_correct' => 'bool',
    ];

    public function correctOptionNumber(): ?int
    {
        for ($i = 1; $i <= 4; $i++) {
            if ($this->{'answer_'.$i.'_correct'}) return $i;
        }
        return null;
    }


}
