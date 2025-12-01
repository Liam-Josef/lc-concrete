<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Lesson;
use App\Models\Role;
use App\Models\State;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class StudentController extends Controller
{

    public function dashboard()
    {
        $user    = auth()->user();
        $student = $user?->student;

        // Your existing list for the table/cards
        $lessons = $student
            ? $student->lessons()->orderBy('lesson_student.created_at','desc')->get()
            : collect();

        // build progress per lesson
        $progress = [];
        if ($student && $lessons->isNotEmpty()) {
            foreach ($lessons as $l) {
                $videoIds  = collect($l->allVideoIds())->unique()->values();
                $total     = $videoIds->count();
                $completed = $total
                    ? DB::table('student_video')
                        ->where('student_id', $student->id)
                        ->whereIn('video_id', $videoIds)
                        ->where('completed', 1)
                        ->count()
                    : 0;

                $progress[$l->id] = [
                    'completed' => $completed,
                    'total'     => $total,
                    'percent'   => $total ? (int) round(($completed / $total) * 100) : 0,
                ];
            }
        }

        // ----- Paid lessons -----
        $paidLessonIds = $student
            ? \App\Models\Invoice::where('student_id', $student->id)->where('paid', 1)->pluck('lesson_id')->unique()
            : collect();
        $lessonsPaidTotal = $paidLessonIds->count();

        // Helper: does this lesson meet completion rules for this student?
        $isLessonComplete = function (\App\Models\Lesson $lesson, int $studentId): bool {
            // 1) Videos
            $videoIds = collect($lesson->allVideoIds());
            $videosCount = $videoIds->count();
            $videosOk = true;
            if ($videosCount > 0) {
                $done = DB::table('student_video')
                    ->where('student_id', $studentId)
                    ->whereIn('video_id', $videoIds)
                    ->where('completed', 1)
                    ->count();
                $videosOk = ($done >= $videosCount);
            }

            // 2) Post assessment (if any) must be >=70% or passed=1
            $postId = DB::table('assessments')
                ->where('lesson_id', $lesson->id)
                ->where('type', 'post')
                ->value('id');

            $postOk = true; // assume ok if no post-test exists
            if ($postId) {
                $latest = DB::table('assessment_attempts')
                    ->where('assessment_id', $postId)
                    ->where('student_id', $studentId)
                    ->orderByDesc('id')
                    ->first();

                $postOk = $latest && (
                        (float)($latest->percent ?? 0) >= 70.0 ||
                        (int)($latest->passed ?? 0) === 1
                    );
            }

            // If there are neither videos nor a post-test, treat as NOT complete.
            $hasAnyRequirement = ($videosCount > 0) || (bool)$postId;

            return $hasAnyRequirement ? ($videosOk && $postOk) : false;
        };

        // ----- Lessons completed among PAID -----
        $lessonsCompletedAmongPaid = 0;
        if ($student && $lessonsPaidTotal > 0) {
            $paidLessons = \App\Models\Lesson::whereIn('id', $paidLessonIds)->get();
            foreach ($paidLessons as $L) {
                if ($isLessonComplete($L, $student->id)) {
                    $lessonsCompletedAmongPaid++;
                }
            }
        }

        // ----- Series (Courses) -----
        // distinct course_ids from PAID lessons (ignore nulls)
        $registeredCourseIds = $lessonsPaidTotal > 0
            ? \App\Models\Lesson::whereIn('id', $paidLessonIds)->whereNotNull('course_id')->pluck('course_id')->unique()
            : collect();
        $seriesTotal = $registeredCourseIds->count();

        // A series is complete only if: student paid for EVERY lesson in that course AND each lesson is complete
        $seriesCompleted = 0;
        if ($student && $seriesTotal > 0) {
            foreach ($registeredCourseIds as $courseId) {
                $courseLessonIds = \App\Models\Lesson::where('course_id', $courseId)->pluck('id');
                if ($courseLessonIds->isEmpty()) continue;

                // Must have paid for all lessons in this course
                $hasAll = $courseLessonIds->every(fn($id) => $paidLessonIds->contains($id));
                if (!$hasAll) continue;

                // Each must be complete
                $allComplete = \App\Models\Lesson::whereIn('id', $courseLessonIds)
                    ->get()
                    ->every(fn($L) => $isLessonComplete($L, $student->id));

                if ($allComplete) $seriesCompleted++;
            }
        }

        // ----- Videos totals across REGISTERED lessons (your existing behavior) -----
        $registeredLessons = $lessons;
        $allVideoIds = collect();
        foreach ($registeredLessons as $l) {
            $allVideoIds = $allVideoIds->merge($l->allVideoIds());
        }
        $allVideoIds = $allVideoIds->unique()->values();
        $videosTotal = $allVideoIds->count();

        $videosCompleted = $student && $videosTotal > 0
            ? DB::table('student_video')
                ->where('student_id', $student->id)
                ->whereIn('video_id', $allVideoIds)
                ->where('completed', 1)
                ->count()
            : 0;

        // Hours watched / total from video_length_seconds
        $videos = $videosTotal
            ? DB::table('videos')->whereIn('id', $allVideoIds)->get(['id','video_length_seconds'])
            : collect();

        $durById      = $videos->pluck('video_length_seconds', 'id');
        $secondsTotal = (int) $videos->sum(fn($v) => (int)($v->video_length_seconds ?? 0));

        $completedVideoIds = $student && $videosTotal
            ? DB::table('student_video')
                ->where('student_id', $student->id)
                ->where('completed', 1)
                ->whereIn('video_id', $allVideoIds)
                ->pluck('video_id')
            : collect();

        $secondsWatched = (int) $completedVideoIds->sum(fn($vid) => (int)($durById[$vid] ?? 0));

        $fmt = function (int $seconds): string {
            $h = intdiv($seconds, 3600);
            $m = intdiv($seconds % 3600, 60);
            return sprintf('%02d:%02d', $h, $m);
        };

        // Student’s enrolled lessons
        $enrolledLessonIds = $student
            ? $student->lessons()->pluck('lessons.id')
            : collect();

// Series (courses table) that those enrolled lessons belong to
        $seriesIds = Lesson::query()
            ->whereIn('id', $enrolledLessonIds)
            ->whereNotNull('course_id')
            ->pluck('course_id')
            ->unique()
            ->values();

// “Other courses in those series” (not yet enrolled)
        $seriesSuggestions = collect();
        if ($seriesIds->isNotEmpty()) {
            $seriesSuggestions = Lesson::with('course') // or ->with('series')
            ->whereIn('course_id', $seriesIds)
                ->whereNotIn('id', $enrolledLessonIds)
                ->when(Schema::hasColumn('lessons', 'is_active'), fn ($q) => $q->where('is_active', 1))
                ->when(Schema::hasColumn('lessons', 'position'), fn ($q) => $q->orderBy('position'))
                ->orderBy('title')
                ->limit(4)
                ->get();
        }


        return view('student.dashboard', [
            'lessons' => $lessons,
            'progress' => $progress,
            'seriesCompleted' => $seriesCompleted,
            'seriesTotal' => $seriesTotal,
            'lessonsCompletedAmongPaid' => $lessonsCompletedAmongPaid,
            'lessonsPaidTotal' => $lessonsPaidTotal,
            'videosCompleted' => $videosCompleted,
            'videosTotal' => $videosTotal,
            'hoursWatchedStr' => $fmt($secondsWatched),
            'hoursTotalStr' => $fmt($secondsTotal),
            'seriesSuggestions' => $seriesSuggestions,
        ]);
    }

    public function course_lesson(Lesson $lesson)
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

        $pre = Assessment::where('lesson_id',$lesson->id)->where('type','pre')->first();
        $hasPreAttempt = $pre ? $pre->attempts()->where('student_id',$studentId)->exists() : false;
        if (!$hasPreAttempt) {
            if (!$pre) { app(\App\Services\AssessmentBuilder::class)->rebuildForLesson($lesson); }
            return redirect()->route('assessments.show', [$lesson->id, 'pre']);
        }

        $videoIds = $lesson->allVideoIds();
        $total = count($videoIds);
        $done  = DB::table('student_video')
            ->where('student_id',$studentId)
            ->whereIn('video_id',$videoIds)
            ->where('completed',1)
            ->count();

        if ($total > 0 && $done >= $total) {
            $post = Assessment::where('lesson_id',$lesson->id)->where('type','post')->first();
            $hasPostAttempt = $post ? $post->attempts()->where('student_id',$studentId)->exists() : false;
            if (!$hasPostAttempt) {
                if (!$post) { app(\App\Services\AssessmentBuilder::class)->rebuildForLesson($lesson); }
                return redirect()->route('assessments.show', [$lesson->id, 'post']);
            }
        }

        $lesson->load('sections.videos.questions');
        $this->applyLessonProgress($lesson); // your existing helper
        $currentVideo = $lesson->sections->first()?->videos->first();
        $nextVideo = $currentVideo ? $this->findNextVideo($lesson, (int)$currentVideo->id) : null;

        return view('student.lessons.course', [
            'lesson'       => $lesson,
            'currentVideo' => $currentVideo,
            'hasNext'      => (bool) $nextVideo,
        ]);
    }

    public function loadVideo(Lesson $lesson, Video $video)
    {
        $lesson->load('sections.videos.questions');
        $this->applyLessonProgress($lesson);
        $nextVideo = $this->findNextVideo($lesson, (int)$video->id);

        return view('student.lessons.course', [
            'lesson'       => $lesson,
            'currentVideo' => $video,
            'hasNext'      => (bool) $nextVideo,
        ]);
    }

    public function nextVideo(Request $request, Lesson $lesson)
    {
        $lesson->load('sections.videos.questions');
        $this->applyLessonProgress($lesson);

        $currentId = (int) $request->input('current_video_id');
        $nextVideo = $this->findNextVideo($lesson, $currentId);

        if (!$nextVideo) {
            return redirect()->route('lessons.start', $lesson);
        }

        return view('student.lessons.course', [
            'lesson'       => $lesson,
            'currentVideo' => $nextVideo,
        ]);
    }

    public function goToNextVideo(Lesson $lesson, Video $currentVideo)
    {
        $lesson->load('sections.videos.questions');
        $this->applyLessonProgress($lesson);

        $nextVideo = $this->findNextVideo($lesson, (int)$currentVideo->id);

        if (!$nextVideo) {
            return redirect()->route('lessons.start', $lesson);
        }

        $nvNext = $this->findNextVideo($lesson, (int)$nextVideo->id);

        return view('student.lessons.course', [
            'lesson'       => $lesson,
            'currentVideo' => $nextVideo,
            'hasNext'      => (bool) $nvNext,
        ]);
    }

    public function submitAnswers(Request $request, \App\Models\Video $video)
    {
        $student   = auth()->user()->student;
        $questions = $video->questions;

        // Validate payload (defensive)
        $request->validate([
            'answers'   => 'required|array',
            'answers.*' => 'required|integer|between:1,4',
        ]);

        $allCorrect = true;

        foreach ($questions as $question) {
            $pickedIdx  = (int) ($request->input("answers.{$question->id}") ?? 0);
            $correctIdx = (int) ($question->correctOptionNumber() ?? 0);
            $isCorrect  = $pickedIdx > 0 && $pickedIdx === $correctIdx;

            // DEBUG: write what the server saw (check storage/logs/laravel.log)
            \Log::debug('QUIZ SUBMIT', [
                'video_id'     => $video->id,
                'question_id'  => $question->id,
                'picked_idx'   => $pickedIdx,
                'correct_idx'  => $correctIdx,
                'is_correct'   => $isCorrect,
            ]);

            // Save per-question outcome (use your real pivot column name)
            $student->questions()->syncWithoutDetaching([
                $question->id => ['completed' => $isCorrect],   // if your column is student_complete, change key
            ]);

            if (! $isCorrect) $allCorrect = false;
        }

        // Mark video as completed only if every question was correct
        if ($allCorrect) {
            $student->videos()->syncWithoutDetaching([
                $video->id => ['completed' => true],
            ]);
        }

        return back()->with([
            'video_passed' => $allCorrect,
            'quiz_failed'  => ! $allCorrect,
        ]);
    }



    private function applyLessonProgress(Lesson $lesson): void
    {
        $student = optional(auth()->user())->student;

        $allIds = collect($lesson->allVideoIds());
        $total  = $allIds->count();

        $completed = 0;
        if ($student && $total > 0) {
            $completed = DB::table('student_video')
                ->where('student_id', $student->id)
                ->whereIn('video_id', $allIds)
                ->where('completed', true)
                ->count();
        }

        $lesson->setAttribute('videos_count', $total);
        $lesson->setAttribute('completed_videos_count', $completed);
    }

    private function findNextVideo(Lesson $lesson, int $currentVideoId): ?Video
    {
        $linear = $lesson->sections->flatMap->videos->values(); // flatten & reindex
        $idx    = $linear->search(fn ($v) => (int) $v->id === $currentVideoId);

        return $idx !== false ? $linear->get($idx + 1) : null;
    }

    public function profileLessonProgress(\App\Models\Lesson $lesson)
    {
        $student = Auth::user()->student ?? null;
        abort_if(!$student, 403);

        $pre  = Assessment::where('lesson_id',$lesson->id)->where('type','pre')->first();
        $post = Assessment::where('lesson_id',$lesson->id)->where('type','post')->first();

        $lastPre  = $pre  ? $pre->attempts()->where('student_id',$student->id)->latest()->first() : null;
        $lastPost = $post ? $post->attempts()->where('student_id',$student->id)->latest()->first() : null;

        $percentPre  = $lastPre?->percent;
        $percentPost = $lastPost?->percent;
        $delta = (!is_null($percentPre) && !is_null($percentPost))
            ? round($percentPost - $percentPre, 2)
            : null;

        return view('student.profile.lesson-progress', compact('lesson','lastPre','lastPost','delta'));
    }

    public function markWatched(\App\Models\Lesson $lesson, \App\Models\Video $video)
    {
        $student = auth()->user()->student;
        abort_if(!$student, 403);

        $student->videos()->syncWithoutDetaching([
            $video->id => ['completed' => true],
        ]);

        return redirect()->route('lesson.goToNextVideo', [$lesson->id, $video->id]);
    }


    public function index() {
        $lessons = Lesson::all();
        return view('admin.students.index', [
            'students' => Student::where('is_active', true)->get(),
            'lessons' => $lessons,
        ]);
    }
    public function inactive() {
        return view('admin.students.inactive', [
            'students' => Student::where('is_active', false)->get()
        ]);
    }
    public function create() {
        return view('admin.students.create', [
            'students' => Student::all(),
            'states' => State::all()
        ]);
    }

    public function view(Student $student) {
        $student->load([
            'lessons.videos',
            'videos'
        ]);
        return view('admin.students.view', [
            'student' => $student
        ]);
    }

    public function edit(Student $student) {
        return view('admin.students.edit', [
            'student' => $student
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'is_active' => 'required|boolean',
            'is_lead' => 'required|boolean',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:50',
            'zip' => 'nullable|string|max:10',
            'company' => 'nullable|string|max:100',
        ]);

        $digits   = sprintf('%04d', random_int(0, 9999));
        $plain    = $validated['last_name'].' '.$digits;
        $password = Hash::make($plain);

        // 1. Create a User
        $user = \App\Models\User::create([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
            'password' => $password,
            'is_admin' => false,
        ]);

        // 2. Attach the "student" role
        $studentRole = \App\Models\Role::where('slug', 'student')->first();
        if ($studentRole) {
            $user->roles()->attach($studentRole->id);
        }

        // 3. Create the Student
        $student = \App\Models\Student::create([
            'is_active' => $validated['is_active'],
            'is_lead' => $validated['is_lead'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'city' => $validated['city'],
            'state' => $validated['state'],
            'zip' => $validated['zip'],
            'company' => $validated['company'],
            'user_id' => $user->id,
        ]);

        return redirect()->route('student.index')->with('success', 'Student and user added successfully!');
    }



    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'is_active' => 'required|boolean',
            'is_lead' => 'required|boolean',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:50',
            'zip' => 'nullable|string|max:10',
            'company' => 'nullable|string|max:100',
        ]);

        $student = Student::findOrFail($id);

        $student->update([
            'is_active' => $validated['is_active'],
            'is_lead' => $validated['is_lead'],
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'city' => $validated['city'],
            'state' => $validated['state'],
            'zip' => $validated['zip'],
            'company' => $validated['company'],
        ]);

        return redirect()->route('student.view', $student->id)->with('success', 'Student updated successfully!');
    }

    public function activate(Student $student)
    {

        $student->update([
            'is_active' => '1',
        ]);

        return redirect()->route('student.index')->with('success', 'Student Activated!');
    }
    public function deactivate(Student $student)
    {

        $student->update([
            'is_active' => '0',
        ]);

        return redirect()->route('student.index')->with('success', 'Student Deactivated!');
    }


    public function lesson(Lesson $lesson, Student $student) {
        $student = auth()->user()->student;

        $lessons = $student
            ? $student->lessons()
                ->withCount('videos')
                ->withCount([
                    'videos as completed_videos_count' => function ($q) use ($student) {
                        $q->whereHas('students', function ($q2) use ($student) {
                            $q2->where('student_id', $student->id)
                                ->where('student_video.completed', true);
                        });
                    },
                ])
                ->get()
            : collect();

        return view('student.lessons.lesson', [
            'lessons' => $lessons
        ]);
    }
    public function becomeStudent(Request $request, ?User $user = null)
    {
        // If an admin is hitting /users/{user}/make-student, $user is set.
        // Otherwise (self-serve), fall back to the authenticated user.
        $target = $user ?? $request->user();

        abort_if(!$target, 403);

        DB::transaction(function () use ($target) {
            // Attach "student" role if you use roles()
            if (method_exists($target, 'roles')) {
                $studentRole = Role::where('slug', 'student')->first();
                if ($studentRole && !$target->roles()->where('roles.id', $studentRole->id)->exists()) {
                    $target->roles()->attach($studentRole->id);
                }
            }

            // Ensure Student profile exists
            if (!$target->student) {
                $first = $target->first_name ?? Str::before($target->name ?? '', ' ');
                $last  = $target->last_name  ?? trim(Str::after($target->name ?? '', ' ')) ?: ' ';
                Student::create([
                    'user_id'    => $target->id,
                    'first_name' => $first,
                    'last_name'  => $last,
                    'email'      => $target->email,
                    'is_active'  => true,
                    'is_lead'    => false,
                ]);
            }
        });

        return back()->with('success', 'Student profile created.');
    }



}
