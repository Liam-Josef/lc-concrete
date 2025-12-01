<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Lesson;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LessonFlowController extends Controller
{
    public function start(Lesson $lesson)
    {
        $student = Auth::user()->student ?? null;
        abort_if(!$student, 403, 'Student profile required.');
        $studentId = $student->id;

        // 1) Must have taken PRE at least once, otherwise send to PRE
        $pre = Assessment::where('lesson_id', $lesson->id)->where('type', 'pre')->first();
        $hasPreAttempt = $pre
            ? $pre->attempts()->where('student_id', $studentId)->exists()
            : false;

        if (!$hasPreAttempt) {
            // Auto-build if an admin hasn’t built yet
            if (!$pre) {
                app(\App\Services\AssessmentBuilder::class)->rebuildForLesson($lesson);
                $pre = Assessment::where('lesson_id', $lesson->id)->where('type', 'pre')->first();
            }
            return redirect()->route('assessments.show', [$lesson->id, 'pre']);
        }

        // 2) If lesson not completed yet, go to lesson player
        if (!$this->isLessonCompleted($lesson, $studentId)) {
            return redirect()->route('student.course_lesson', $lesson->id);
        }

        // 3) Lesson is completed — if POST not yet taken, send to POST
        $post = Assessment::where('lesson_id', $lesson->id)->where('type', 'post')->first();
        $hasPostAttempt = $post
            ? $post->attempts()->where('student_id', $studentId)->exists()
            : false;

        if (!$hasPostAttempt) {
            if (!$post) {
                app(\App\Services\AssessmentBuilder::class)->rebuildForLesson($lesson);
                $post = Assessment::where('lesson_id', $lesson->id)->where('type', 'post')->first();
            }
            return redirect()->route('assessments.show', [$lesson->id, 'post']);
        }

        // 4) Both pre and post are done — show progress
        return redirect()->route('assessments.progress', $lesson->id);
    }

    /** Lesson completion = all lesson videos completed by this student. */
    private function isLessonCompleted(Lesson $lesson, int $studentId): bool
    {
        $videoIds = $lesson->allVideoIds(); // from the helper we added earlier
        if (empty($videoIds)) return false;

        $total = count($videoIds);
        $done  = DB::table('student_video')
            ->where('student_id', $studentId)
            ->whereIn('video_id', $videoIds)
            ->where('completed', 1)
            ->count();

        return $done >= $total && $total > 0;
    }
}
