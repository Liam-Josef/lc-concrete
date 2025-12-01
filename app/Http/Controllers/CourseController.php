<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseController extends Controller
{

    public function index()
    {
        $courses = \App\Models\Course::with('organization')
            ->where('is_active', true)
            ->withCount('lessons')
            ->addSelect([
                // Count DISTINCT videos reachable either directly from lessons OR via sections
                'videos_count' => function ($q) {
                    $q->from('lessons')
                        // link sections via pivot
                        ->leftJoin('lesson_section as ls', 'ls.lesson_id', '=', 'lessons.id')
                        ->leftJoin('section_video as sv', 'sv.section_id', '=', 'ls.section_id')
                        // join videos once and match either via section_video or direct lesson_id
                        ->leftJoin('videos as v', function ($join) {
                            $join->on('v.id', '=', 'sv.video_id')
                                ->orOn('v.lesson_id', '=', 'lessons.id');
                        })
                        ->whereColumn('lessons.course_id', 'courses.id')
                        ->selectRaw('COUNT(DISTINCT v.id)');
                },

                // Distinct students enrolled in ANY lesson in the course
                'students_count' => function ($q) {
                    $q->from('lesson_student')
                        ->join('lessons', 'lessons.id', '=', 'lesson_student.lesson_id')
                        ->whereColumn('lessons.course_id', 'courses.id')
                        ->selectRaw('COUNT(DISTINCT lesson_student.student_id)');
                },
            ])
            ->orderBy('title')
            ->get();

        return view('admin.courses.index', compact('courses'));
    }

    // STEP 1 (GET) /admin/courses/create
    public function createStep1(Request $request)
    {
        $orgs = Organization::orderBy('name')->get();
        $selectedOrg = $request->query('org');
        return view('admin.courses.create-step1', compact('orgs', 'selectedOrg'));
    }

    // STEP 1 (POST) → redirect to Step 2
    public function postStep1(Request $request)
    {
        $data = $request->validate([
            'org_id' => 'required|exists:organizations,id',
        ]);

        return redirect()->route('admin.courses.create.details', ['org' => $data['org_id']]);
    }

    // STEP 2 (GET) /admin/courses/create/details?org=ID
    public function createStep2(Request $request)
    {
        $orgId = (int) $request->query('org');
        $org   = Organization::find($orgId);

        if (!$org) {
            return redirect()->route('admin.courses.create')
                ->withErrors('Please choose a valid organization.');
        }

        return view('admin.courses.create-step2', compact('org'));
    }

    // POST /admin/courses
    public function store(Request $request)
    {
        $data = $request->validate([
            'org_id'            => 'required|exists:organizations,id',
            'title'             => 'required|string|max:255',
            'is_active'         => 'sometimes|boolean',
            'short_description' => 'nullable|string|max:255',
            'long_description'  => 'nullable|string',
            'image'             => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:4096',
            'image_title'       => 'nullable|string|max:255',
        ]);

        $payload = $data;
        $payload['is_active'] = (bool)($data['is_active'] ?? true);

        if ($request->hasFile('image')) {
            $payload['image'] = $request->file('image')->store('courses', 'public');
        }

        $payload['slug'] = $this->uniqueSlug($data['title']);

        $course = \App\Models\Course::create($payload);

        return redirect()->route('admin.courses.show', $course)
            ->with('success', 'Course created. You can now add lessons.');
    }

    // GET /admin/courses/{course}
    public function show(Course $course)
    {
        $course->load(['organization','lessons.videos']);
        return view('admin.courses.show', compact('course'));
    }

    public function quickStore(Request $request)
    {
        $data = $request->validate([
            'org_id' => 'required|exists:organizations,id',
            'title'  => 'required|string|max:255',
        ]);

        $base = \Illuminate\Support\Str::slug($data['title']);
        $slug = $base; $i = 2;
        while (\App\Models\Course::where('slug',$slug)->exists()) $slug = $base.'-'.$i++;

        $course = \App\Models\Course::create([
            'org_id'    => $data['org_id'],
            'title'     => $data['title'],
            'slug'      => $slug,
            'is_active' => 1,
        ]);

        // Return to lesson create with both selected
        return redirect()->route('lesson.create', ['org' => $course->org_id, 'course' => $course->id])
            ->with('success','Course created.');
    }

    public function edit(Course $course)
    {
        $organizations = Organization::orderBy('name')->get();

        // keep page fast; counts are only for display if you want
        $course->load('organization');

        return view('admin.courses.edit', compact('course','organizations'));
    }

    public function update(Request $request, Course $course)
    {
        // If org change is allowed, use 'sometimes'; otherwise drop this rule entirely.
        $rules = [
            'org_id'            => 'sometimes|exists:organizations,id',
            'title'             => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'long_description'  => 'nullable|string',
            'is_active'         => 'sometimes|boolean',
            'image'             => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:4096',
            // only validate 'position' if the column exists
        ];
        if (Schema::hasColumn('courses', 'position')) {
            $rules['position'] = 'nullable|integer|min:0';
        }

        $data = $request->validate($rules);

        // Normalize booleans
        $data['is_active'] = $request->has('is_active')
            ? $request->boolean('is_active')
            : $course->is_active;

        if ($course->title !== $data['title'] || empty($course->slug)) {
            $data['slug'] = $this->uniqueSlug($data['title'], $course->id);
        }

        // Handle image removal
        if ($request->boolean('remove_image')) {
            if ($course->image) {
                Storage::disk('public')->delete($course->image);
            }
            $data['image'] = null;
        }
        // Handle new upload
        elseif ($request->hasFile('image')) {
            if ($course->image) {
                Storage::disk('public')->delete($course->image);
            }
            $data['image'] = $request->file('image')->store('courses', 'public');
        }

        // Optional slug sync if the column exists
        if (Schema::hasColumn('courses', 'slug') && !empty($data['title'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $course->fill($data)->save();

        return redirect()
            ->route('admin.courses.show', $course)
            ->with('success', 'Course updated successfully.');
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        if ($base === '') {
            $base = Str::random(8);
        }
        $slug = $base;
        $n = 2;

        $exists = fn($s) => Course::where('slug', $s)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists();

        while ($exists($slug)) {
            $slug = "{$base}-{$n}";
            $n++;
        }

        return $slug;
    }

    public function byOrg(\App\Models\Organization $org)
    {
        $courses = \App\Models\Course::where('org_id', $org->id)
            ->where('is_active', true)
            ->orderBy('title')
            ->get(['id','title']);

        return response()->json($courses);
    }

    public function deactivate(Course $course)
    {
        $course->update([
            'is_active' => 0,
        ]);

        // Optional: also deactivate all lessons in this series
        // $course->lessons()->update(['is_active' => 0]);

        return redirect()
            ->back()
            ->with('success', 'Course series deactivated.');
    }



}
