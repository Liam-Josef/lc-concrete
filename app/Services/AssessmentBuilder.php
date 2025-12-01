<?php

// app/Services/AssessmentBuilder.php
namespace App\Services;

use App\Models\Assessment;
use App\Models\Lesson;

class AssessmentBuilder
{
    public function rebuildForLesson(Lesson $lesson): array
    {
        $qids = $lesson->allQuestionIds();

        $attach = [];
        $pos = 1;
        foreach ($qids as $qid) {
            $attach[$qid] = ['position' => $pos++];
        }

        $pre  = Assessment::firstOrCreate(
            ['lesson_id' => $lesson->id, 'type' => 'pre'],
            ['title' => "Pre-test: {$lesson->title}"]
        );
        $post = Assessment::firstOrCreate(
            ['lesson_id' => $lesson->id, 'type' => 'post'],
            ['title' => "Post-test: {$lesson->title}"]
        );

        $pre->questions()->sync($attach);
        $post->questions()->sync($attach);

        return [$pre, $post];
    }
}
