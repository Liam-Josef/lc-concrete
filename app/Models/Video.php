<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected  $guarded = [];

    public function sections() {
        return $this->belongsToMany(Section::class);
    }
    public function students() {
        return $this->belongsToMany(Student::class)->withPivot('completed','completed_at')->withTimestamps();
    }
    public function questions() {
        return $this->belongsToMany(Question::class);
    }
    public function lessons() {
        return $this->belongsToMany(Lesson::class);
    }

    public function getVideoLengthFormattedAttribute(): ?string
    {
        if ($this->video_length_seconds === null) {
            return null;
        }
        $m = intdiv($this->video_length_seconds, 60);
        $s = $this->video_length_seconds % 60;
        return sprintf('%d:%02d', $m, $s);
    }


}
