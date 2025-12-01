<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\AssessmentAttempt;
use App\Models\AssessmentAttemptAnswer;
use App\Models\Lesson;
use App\Models\Question;
use App\Services\AssessmentBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AssessmentController extends Controller
{
    // Admin action — build/refresh pre & post from lesson questions
    public function rebuild(Lesson $lesson, AssessmentBuilder $builder)
    {
        [$pre, $post] = $builder->rebuildForLesson($lesson);
        $count = $pre->questions()->count(); // same as post
        return back()->with('success', "PRE & POST synced. {$count} questions attached.");
    }

    // Student sees test
    public function show(Lesson $lesson, string $type)
    {

        $student = Auth::user()->student ?? null;
        abort_if(!$student, 403);
        $studentId = $student->id;

        $hasAccess = $student->lessons()
            ->where('lessons.id', $lesson->id)
            ->wherePivot('access_granted', true)
            ->exists();

        if (!$hasAccess) {
            // Prevent ID guessing
            return redirect()
                ->route('home.course_lesson', $lesson->id)
                ->with('error', 'You are not enrolled in this course.');
        }
        abort_unless(in_array($type, ['pre','post'], true), 404);

        $assessment = \App\Models\Assessment::where('lesson_id',$lesson->id)
            ->where('type',$type)
            ->first();

        if (!$assessment) {
            // build on the fly if not present at all
            [$pre, $post] = app(\App\Services\AssessmentBuilder::class)->rebuildForLesson($lesson);
            $assessment = $type === 'pre' ? $pre : $post;
        }

        // If present but empty, optionally refresh
        if ($assessment->questions()->count() === 0) {
            app(\App\Services\AssessmentBuilder::class)->rebuildForLesson($lesson);
        }

        $assessment->load('questions');

        return view('student.lessons.assessment.take', [
            'lesson'     => $lesson,
            'assessment' => $assessment,
            'questions'  => $assessment->questions,
        ]);
    }

    // Student submits test
    public function submit(Request $request, Assessment $assessment)
    {
        $student = auth()->user()->student;
        abort_if(!$student, 403);

        // Sanity debug on prod
        Log::debug('assessments.submit payload', $request->all());

        // Radios: q[QUESTION_ID] = 1..4
        $data = $request->validate([
            'q'          => 'required|array',
            'q.*'        => 'required|integer|in:1,2,3,4',
            'started_at' => 'nullable|date',
        ]);

        $answersMap = $data['q'];   // [question_id => pickedOptionNumber]
        $questions  = $assessment->questions()->get(); // via assessment_question pivot
        $total      = $questions->count();
        $score      = 0;

        // parent attempt
        $attempt = AssessmentAttempt::create([
            'assessment_id' => $assessment->id,
            'student_id'    => $student->id,
            'score'         => 0,                // fill after grading
            'total'         => $total,
            'percent'       => 0,                // fill after grading
            'started_at'    => $data['started_at'] ?? now(),
            'finished_at'   => now(),
        ]);

        foreach ($questions as $q) {
            $picked = (int)($answersMap[$q->id] ?? 0);   // 1..4 or 0
            $isCorrect = $picked > 0 ? $q->isOptionCorrect($picked) : false;

            if ($isCorrect) {
                $score++;
            }

            AssessmentAttemptAnswer::create([
                'assessment_attempt_id' => $attempt->id,
                'question_id'           => $q->id,
                'selected_option'       => $picked ?: null,
                'is_correct'            => $isCorrect,
            ]);
        }

        $percent = $total > 0 ? round(($score / $total) * 100, 2) : 0;

        $attempt->update([
            'score'   => $score,
            'percent' => $percent,
        ]);

        // Where to next
        if ($assessment->type === 'pre') {
            return redirect()
                ->route('student.course_lesson', $assessment->lesson_id)
                ->with('success', 'Pre-test submitted.');
        }

        return redirect()
            ->route('assessments.progress', $assessment->lesson_id)
            ->with('success', 'Post-test submitted.');
    }

    // Student progress page: compare last PRE vs last POST
    public function progress(Lesson $lesson)
    {
        $studentId = Auth::user()->student->id ?? null;
        abort_if(!$studentId, 403);

        $pre  = Assessment::where('lesson_id',$lesson->id)->where('type','pre')->first();
        $post = Assessment::where('lesson_id',$lesson->id)->where('type','post')->first();

        $lastPre  = $pre  ? $pre->attempts()->where('student_id',$studentId)->latest()->first() : null;
        $lastPost = $post ? $post->attempts()->where('student_id',$studentId)->latest()->first() : null;

        return view('student.lessons.assessment.progress', [
            'lesson'   => $lesson,
            'lastPre'  => $lastPre,
            'lastPost' => $lastPost,
            'delta'    => ($lastPre && $lastPost) ? round($lastPost->percent - $lastPre->percent, 2) : null,
        ]);
    }
}
