<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class InstructorController extends Controller
{

    public function index()
    {
        $instructors = \App\Models\Instructor::query()
            ->where('is_active', true)
            ->with(['lessons:id,title'])   // load lesson titles for listing
            ->withCount('lessons')         // fast count for the badge/column
            ->get();

        return view('admin.instructors.index', compact('instructors'));
    }
    public function inactive() {
        return view('admin.instructors.inactive', [
            'lessons' => Instructor::where('is_active', false)->get()
        ]);
    }
    public function create() {
        return view('admin.instructors.create', [
            'instructors' => Instructor::all(),
            'organizations' => Organization::all(),
        ]);
    }

    public function view(Instructor $instructor)
    {
        // Eager-load lessons + series + both video paths (direct + via sections)
        $instructor->load([
            'lessons' => fn ($q) => $q->orderBy('title'),
            'lessons.course:id,title',          // series
            'lessons.videos:id,lesson_id',      // direct lesson->videos
            'lessons.sections:id,lesson_id',
            'lessons.sections.videos:id',       // videos via sections
        ]);

        return view('admin.instructors.view', compact('instructor'));
    }


    public function edit(Instructor $instructor) {
        return view('admin.instructors.edit', [
            'instructor' => $instructor,
            'organizations' => Organization::all()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'is_active' => 'required|in:0,1',
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'phone'      => 'nullable|string|max:20',
            'email'      => 'required|email|max:255|unique:users,email',
            'organization'     => 'nullable|max:255',
            'bio'        => 'nullable|string',
            'image'      => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $digits   = sprintf('%04d', random_int(0, 9999));
        $plain    = $validated['last_name'].' '.$digits;
        $password = Hash::make($plain);

        $user = \App\Models\User::create([
            'name'     => $validated['first_name'].' '.$validated['last_name'],
            'email'    => $validated['email'],
            'password' => $password,
            'is_admin' => false,
        ]);

        if ($roleId = \App\Models\Role::where('slug', 'instructor')->value('id')) {
            $user->roles()->attach($roleId);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('instructor-images', 'public');
        }

        $instructor = \App\Models\Instructor::create([
            'is_active'   => (int) $validated['is_active'],
            'first_name'  => $validated['first_name'],
            'last_name'   => $validated['last_name'],
            'phone'       => $validated['phone'] ?? null,
            'email'       => $validated['email'],
            'bio'         => $validated['bio'] ?? null,
            'image'       => $imagePath,
            'user_id'     => $user->id,
            'org_id'      => $validated['org_id'] ?? null,
            'organization'=>  $validated['organization'],
        ]);

         if (!empty($validated['org_id'])) {
             $instructor->organizations()->syncWithoutDetaching([$validated['org_id']]);
         }

        return redirect()->route('instructor.index')
            ->with('success', 'Instructor and user added successfully!');
    }

    public function update(Request $request, Instructor $instructor)
    {
        $validated = $request->validate([
            'is_active'    => ['required','in:0,1'],
            'first_name'   => ['required','string','max:191'],
            'last_name'    => ['required','string','max:191'],
            'organization' => ['nullable','string','max:191'],
            'phone'        => ['nullable','string','max:50'],
            'email'        => [
                'required','email','max:191',
                Rule::unique('instructors','email')->ignore($instructor->id)
            ],
            'bio'          => ['nullable','string'],
            'image'        => ['nullable','image','mimes:jpg,jpeg,png,webp,gif','max:4096'],
        ]);

        // normalize phone (optional)
        if (!empty($validated['phone'])) {
            $digits = preg_replace('/\D+/', '', $validated['phone']);
            if (strlen($digits) === 10) {
                $validated['phone'] = substr($digits,0,3).'-'.substr($digits,3,3).'-'.substr($digits,6);
            }
        }

        // handle image upload
        if ($request->hasFile('image')) {
            // delete old if exists
            if (!empty($instructor->image)) {
                Storage::disk('public')->delete($instructor->image);
            }
            $validated['image'] = $request->file('image')->store('instructor-images','public');
        } else {
            unset($validated['image']); // keep existing
        }

        $instructor->update($validated);

        return redirect()
            ->route('instructor.edit', $instructor->id)
            ->with('success', 'Instructor updated successfully.');
    }

    public function quickStore(Request $request)
    {
        $data = $request->validate([
            'first_name'   => 'required|string|max:120',
            'last_name'    => 'required|string|max:120',
            'organization' => 'nullable|string|max:190',
        ]);

        $ins = \App\Models\Instructor::create([
            'is_active'    => true,
            'first_name'   => $data['first_name'],
            'last_name'    => $data['last_name'],
            'organization' => $data['organization'] ?? null,
        ]);

        return response()->json([
            'ok' => 1,
            'instructor' => [
                'id'   => $ins->id,
                'name' => trim(($ins->first_name ?? '').' '.($ins->last_name ?? '')),
            ],
        ]);
    }




}
