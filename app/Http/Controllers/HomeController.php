<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\News;
use App\Models\State;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $news = News::latest()->get();


        return view('home.index', compact('news'));
    }

    public function portfolio() {
        return view('home.business.portfolio.index');
    }

    public function contact() {
        return view('home.business.contact.index');
    }



    public function courses()
    {
        $shippingCourse = \App\Models\Course::where('title', 'Shipping Education Series')->first();

        // All active courses EXCEPT Shipping Education Series
        $otherCourses = \App\Models\Course::query()
            ->withCount('lessons')
            ->with('organization')
            ->where('is_active', true)
            ->when($shippingCourse, fn($q) => $q->where('id', '!=', $shippingCourse->id))
            // (extra guard if title uniqueness is uncertain)
            ->where('title', '!=', 'Shipping Education Series')
            ->orderBy('title')
            ->get();

        // All active standalone lessons (no course_id)
        $standaloneLessons = \App\Models\Lesson::query()
            ->where('is_active', 1)
            ->whereNull('course_id')
            ->orderBy('title')
            ->get();

        return view('home.courses', compact('shippingCourse', 'otherCourses', 'standaloneLessons'));
    }



    public function courseShow(Course $course)
    {
        $course->load([
            'organization',
            'lessons' => fn ($q) => $q->where('is_active', true)->orderBy('id')
        ]);

        return view('home.course-show', compact('course'));
    }
    public function course()
    {
        $lessons = Lesson::all();
        return view('home.course', [
            'lessons' => $lessons
        ]);
    }
    public function course_lesson(Lesson $lesson)
    {
        // Load everything we need
        $lesson->load(['sections.videos', 'course']);

        // Counts
        $sectionCount = $lesson->sections->count();
        $videoCount   = $lesson->sections->flatMap->videos->count();

        // Sum total seconds from all videos (parsing flexible formats)
        $totalSeconds = $lesson->sections->flatMap->videos->sum(
            fn ($v) => $this->parseVideoDurationToSeconds($v->video_length ?? '')
        );

        // Round up to full hours
        $durationHours = (int) ceil($totalSeconds / 3600);

        // Related lessons (your existing logic)
        $courseId = $lesson->course_id;
        $orgId    = optional($lesson->course)->org_id ?? $lesson->org_id;

        $sameCourse = collect();
        if ($courseId) {
            $sameCourse = \App\Models\Lesson::where('course_id', $courseId)
                ->where('id', '!=', $lesson->id)
                ->where('is_active', 1)
                ->orderBy('id')
                ->take(3)
                ->get();
        }

        $needed  = 3 - $sameCourse->count();
        $sameOrg = collect();
        if ($needed > 0 && $orgId) {
            $excludeIds = $sameCourse->pluck('id')->push($lesson->id);
            $sameOrg = \App\Models\Lesson::where('org_id', $orgId)
                ->whereNotIn('id', $excludeIds)
                ->when($courseId, fn ($q) => $q->where(function ($qq) use ($courseId) {
                    $qq->whereNull('course_id')->orWhere('course_id', '!=', $courseId);
                }))
                ->where('is_active', 1)
                ->orderBy('id')
                ->take($needed)
                ->get();
        }
        $lessons = $sameCourse->concat($sameOrg);

        $states = State::orderBy('state')->get(['code','state']);

        // 👇 grab your single apps/settings row
        $settings = AppSetting::first();   // change class if needed

        return view('home.course-lesson', [
            'lesson'        => $lesson,
            'lessons'       => $lessons,
            'sectionCount'  => $sectionCount,
            'videoCount'    => $videoCount,
            'durationHours' => $durationHours,
            'states'        => $states,
            'settings'      => $settings,
        ]);
    }

    /**
     * Parse a flexible duration string to seconds.
     * Accepts: "HH:MM:SS", "MM:SS", "1h 20m 5s", "90" (minutes), "1.5" (hours), etc.
     */
    private function parseVideoDurationToSeconds(?string $raw): int
    {
        if (!$raw) return 0;
        $s = trim(strtolower($raw));

        // HH:MM:SS
        if (preg_match('/^(\d+):([0-5]?\d):([0-5]?\d)$/', $s, $m)) {
            return (int)$m[1]*3600 + (int)$m[2]*60 + (int)$m[3];
        }
        // MM:SS
        if (preg_match('/^(\d+):([0-5]?\d)$/', $s, $m)) {
            return (int)$m[1]*60 + (int)$m[2];
        }
        // 1h 20m 5s / 1 hour 20 min 5 sec (any subset)
        if (preg_match('/(?:(\d+)\s*h(?:ours?)?)?\s*(?:(\d+)\s*m(?:in(?:utes?)?)?)?\s*(?:(\d+)\s*s(?:ec(?:onds?)?)?)?/', $s, $m)) {
            $h = (int)($m[1] ?? 0);
            $mi= (int)($m[2] ?? 0);
            $se= (int)($m[3] ?? 0);
            if ($h || $mi || $se) return $h*3600 + $mi*60 + $se;
        }
        // Plain number: assume hours if small float, else minutes
        if (is_numeric($s)) {
            $f = (float)$s;
            if ($f > 0 && $f <= 24) {           // treat as hours
                return (int) round($f * 3600);
            }
            return (int) round($f * 60);         // treat as minutes
        }
        return 0;
    }


    public function terms()
    {
        $lessons = Lesson::all();
        return view('home.terms-and-conditions', [
            'lessons' => $lessons
        ]);
    }
    public function privacy()
    {
        $lessons = Lesson::all();
        return view('home.privacy-policy', [
            'lessons' => $lessons
        ]);
    }


}
