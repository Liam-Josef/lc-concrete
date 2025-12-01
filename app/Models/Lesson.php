<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Lesson extends Model
{
    use HasFactory;

    protected  $guarded = [];

    public function students() {
        return $this->belongsToMany(Student::class)->withPivot('complete')->withTimestamps();
    }
    public function instructors() {
        return $this->belongsToMany(Instructor::class);
    }
    public function videos() {
        return $this->hasMany(Video::class);
    }
    public function organization() {
        return $this->belongsTo(Organization::class, 'org_id');
    }

    public function sections() {
        return $this->belongsToMany(Section::class)->withPivot('section_number')->withTimestamps();
    }
    public function course()
    {
        // assumes lessons table has course_id
        return $this->belongsTo(\App\Models\Course::class, 'course_id');
    }
    public function courses() {
        return $this->belongsTo(Course::class);
    }
    /** Collect ALL video IDs connected to this lesson (any path). */
    public function allVideoIds(): array
    {
        // A) videos.lesson_id = this lesson
        $direct = DB::table('videos')
            ->where('lesson_id', $this->id)
            ->pluck('id');

        // B) lesson_video pivot (lesson <-> videos)
        $viaLessonPivot = DB::table('lesson_video')
            ->where('lesson_id', $this->id)
            ->pluck('video_id');

        // C) lesson -> lesson_section -> section_video -> videos
        $sectionIds = DB::table('lesson_section')
            ->where('lesson_id', $this->id)
            ->pluck('section_id');

        $viaSectionPivot = $sectionIds->isEmpty()
            ? collect()
            : DB::table('section_video')
                ->whereIn('section_id', $sectionIds)
                ->pluck('video_id');

        return $direct
            ->merge($viaLessonPivot)
            ->merge($viaSectionPivot)
            ->unique()
            ->values()
            ->all();
    }

    /** Collect ALL question IDs via question_video for this lesson’s videos. */
    public function allQuestionIds(): array
    {
        $videoIds = collect($this->allVideoIds());
        if ($videoIds->isEmpty()) return [];

        return DB::table('question_video')
            ->whereIn('video_id', $videoIds)
            ->pluck('question_id')
            ->unique()
            ->values()
            ->all();
    }

    public function certificates()
    {
        return $this->hasMany(\App\Models\Certificate::class);
    }





}
