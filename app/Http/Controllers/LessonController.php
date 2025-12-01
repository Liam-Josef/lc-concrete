<?php

namespace App\Http\Controllers;

use App\Models\App as AppSetting;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\Invoice;
use App\Models\Organization;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Lesson;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\CourseRegistrationReceipt;
use App\Mail\NewRegistrationAlert;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Throwable;
use Illuminate\Validation\ValidationException;


class LessonController extends Controller
{
    public function index()
    {
        $lessons = Lesson::query()
            ->with([
                'instructors',
                'course:id,title,org_id',
                'course.organization:id,name',
            ])
            ->addSelect([
                'videos_count' => function ($q) {
                    $q->from('videos')
                        ->selectRaw('COUNT(DISTINCT videos.id)')
                        ->where(function ($w) {
                            $w->whereColumn('videos.lesson_id', 'lessons.id')
                                ->orWhereExists(function ($sub) {
                                    $sub->from('section_video')
                                        ->join('lesson_section', 'lesson_section.section_id', '=', 'section_video.section_id')
                                        ->whereColumn('lesson_section.lesson_id', 'lessons.id')
                                        ->whereColumn('section_video.video_id', 'videos.id');
                                });
                        });
                },
                'students_count' => function ($q) {
                    $q->from('lesson_student')
                        ->selectRaw('COUNT(DISTINCT lesson_student.student_id)')
                        ->whereColumn('lesson_student.lesson_id', 'lessons.id');
                },
            ])
            ->where('is_active', true)
            ->orderBy('title')
            ->get();

        return view('admin.lessons.index', compact('lessons'));
    }


    public function inactive() {
        return view('admin.lessons.inactive', [
            'lessons' => Lesson::where('is_active', false)->get()
        ]);
    }
    public function create(Request $request)
    {
        $selectedCourse = null;
        $selectedOrg    = null;

        if ($request->filled('course')) {
            $selectedCourse = \App\Models\Course::with('organization')->find($request->query('course'));
            $selectedOrg    = $selectedCourse?->org_id;
        } elseif ($request->filled('org')) {
            $selectedOrg = (int) $request->query('org');
        }

        $organizations = \App\Models\Organization::select('id','name')->orderBy('name')->get();
        $courses       = \App\Models\Course::select('id','org_id','title')->orderBy('title')->get();
        $instructors   = \App\Models\Instructor::select('id','first_name','last_name','image')
            ->orderBy('first_name')->orderBy('last_name')->get();

        $preselectInstructorId = $request->integer('instructor_id') ?: null;

        return view('admin.lessons.create', compact(
            'organizations','courses','selectedOrg','selectedCourse','instructors','preselectInstructorId'
        ));
    }



    public function view(Lesson $lesson)
    {
        $lesson->load('sections.videos');

        return view('admin.lessons.view', [
            'lesson' => $lesson
        ]);
    }

    public function edit(Request $request, $id)
    {
        $lesson = Lesson::with('course')->findOrFail($id);

        // exactly like the Add page expects
        $organizations = Organization::orderBy('name')->get(['id','name']);
        $courses       = Course::orderBy('title')->get(['id','org_id','title']);
        $instructors   = Instructor::orderBy('first_name')->orderBy('last_name')
            ->get(['id','first_name','last_name','image']);

        // selected values to pre-populate the pickers
        $selectedOrg    = $lesson->org_id ?: optional($lesson->course)->org_id;
        $selectedCourse = $lesson->course; // could be null

        // preselect instructor (no column on lessons; read from pivots or query)
        $preselectInstructorId = $request->integer('instructor_id') ?: null;
        if (!$preselectInstructorId) {
            if (Schema::hasTable('instructor_lesson')) {
                $preselectInstructorId = DB::table('instructor_lesson')
                    ->where('lesson_id', $lesson->id)->value('instructor_id');
            } elseif (Schema::hasTable('lesson_instructor')) {
                $preselectInstructorId = DB::table('lesson_instructor')
                    ->where('lesson_id', $lesson->id)->value('instructor_id');
            }
        }
        if (!$preselectInstructorId && $lesson->course_id && Schema::hasTable('course_instructor')) {
            $preselectInstructorId = DB::table('course_instructor')
                ->where('course_id', $lesson->course_id)->value('instructor_id');
        }

        return view('admin.lessons.edit', compact(
            'lesson',
            'organizations',
            'courses',
            'instructors',
            'selectedOrg',
            'selectedCourse',
            'preselectInstructorId'
        ));
    }

    public function register(Lesson $lesson) {
        return view('student.lessons.register', [
            'lesson' => $lesson,
            'organizations' => Organization::all()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'org_id'      => 'required|exists:organizations,id',
            'course_id'   => 'nullable|exists:courses,id', // series optional
            'is_active'   => 'required|boolean',
            'title'       => 'required|string|max:255',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:4096',
            'video'       => 'nullable|string|max:255',

            'short_description'        => 'nullable|string|max:255',
            'long_description'         => 'nullable|string',
            'learning_outcomes'        => 'nullable|string',
            'course_notes'             => 'nullable|string',
            'completion_requirements'  => 'nullable|string',
            'event_link'               => 'nullable|string|max:255',
            'total_hours'              => 'nullable|string|max:255',
            'total_ceu'                => 'nullable|string|max:255',
            'student_cost'             => 'nullable|string|max:255',
            'platform_cost'            => 'nullable|string|max:255',
            'pay_to_organization'      => 'nullable|string|max:255',

            'instructor_id'            => 'nullable|exists:instructors,id',
        ]);

        $organization = \App\Models\Organization::find($validated['org_id']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('lesson-images', 'public');
        }

        $lesson = \App\Models\Lesson::create([
            'is_active'     => (int)$validated['is_active'],
            'title'         => $validated['title'],
            'image'         => $validated['image'] ?? null,
            'video'         => $validated['video'] ?? null,

            // org + (optional) series
            'org_id'        => $validated['org_id'],
            'organization'  => $organization?->name,
            'course_id'     => $validated['course_id'] ?? null,

            // optional text fields
            'short_description'        => $validated['short_description'] ?? null,
            'long_description'         => $validated['long_description'] ?? null,
            'learning_outcomes'        => $validated['learning_outcomes'] ?? null,
            'course_notes'             => $validated['course_notes'] ?? null,
            'completion_requirements'  => $validated['completion_requirements'] ?? null,
            'event_link'               => $validated['event_link'] ?? null,
            'total_hours'              => $validated['total_hours'] ?? null,
            'total_ceu'                => $validated['total_ceu'] ?? null,
            'student_cost'             => $validated['student_cost'] ?? null,
            'platform_cost'            => $validated['platform_cost'] ?? null,
            'pay_to_organization'      => $validated['pay_to_organization'] ?? null,
        ]);

        if ($request->filled('instructor_id')) {
            $lesson->instructors()->syncWithoutDetaching([(int)$request->input('instructor_id')]);
        }

        return redirect($request->input('redirect_url', route('lesson.view', $lesson->id)))
            ->with('success', 'Lesson added successfully!');
    }


    public function update(Request $request, $id)
    {
        $lesson = \App\Models\Lesson::findOrFail($id);

        $validated = $request->validate([
            'is_active' => 'required|in:0,1',
            'title'     => 'required|string|max:255',

            'org_id'          => 'nullable|exists:organizations,id',
            'organization_id' => 'nullable|exists:organizations,id',
            'course_id'       => 'nullable|exists:courses,id',
            'instructor_id'   => 'nullable|exists:instructors,id',   // NEW

            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:4096',
            'video' => 'nullable|string|max:255',

            'short_description'        => 'nullable|string|max:255',
            'long_description'         => 'nullable|string',
            'learning_outcomes'        => 'nullable|string',
            'course_notes'             => 'nullable|string',
            'completion_requirements'  => 'nullable|string',
            'event_link'               => 'nullable|string|max:255',
            'total_hours'              => 'nullable|string|max:255',
            'total_ceu'                => 'nullable|string|max:255',
            'student_cost'             => 'nullable|string|max:255',
            'platform_cost'            => 'nullable|string|max:255',
            'pay_to_organization'      => 'nullable|string|max:255',
        ]);

        // Resolve org and course like before
        $orgId    = $validated['org_id'] ?? $validated['organization_id'] ?? $lesson->org_id;
        $courseId = $validated['course_id'] ?? $lesson->course_id;

        if ($courseId) {
            $course = \App\Models\Course::find($courseId);
            if (!$course) {
                return back()->withErrors('Selected course was not found.')->withInput();
            }
            if ($orgId && (int)$course->org_id !== (int)$orgId) {
                return back()->withErrors('Selected course does not belong to the chosen organization.')->withInput();
            }
        }

        $update = [
            'is_active'               => (int)$validated['is_active'],
            'title'                   => $validated['title'],
            'short_description'       => $validated['short_description']       ?? $lesson->short_description,
            'long_description'        => $validated['long_description']        ?? $lesson->long_description,
            'learning_outcomes'       => $validated['learning_outcomes']       ?? $lesson->learning_outcomes,
            'course_notes'            => $validated['course_notes']            ?? $lesson->course_notes,
            'completion_requirements' => $validated['completion_requirements'] ?? $lesson->completion_requirements,
            'event_link'              => $validated['event_link']              ?? $lesson->event_link,
            'total_hours'             => $validated['total_hours']             ?? $lesson->total_hours,
            'total_ceu'               => $validated['total_ceu']               ?? $lesson->total_ceu,
            'student_cost'            => $validated['student_cost']            ?? $lesson->student_cost,
            'platform_cost'           => $validated['platform_cost']           ?? $lesson->platform_cost,
            'pay_to_organization'     => $validated['pay_to_organization']     ?? $lesson->pay_to_organization,
            'video'                   => $validated['video']                   ?? $lesson->video,
            'course_id'               => $courseId,
        ];

        if ($orgId) {
            $org = \App\Models\Organization::find($orgId);
            $update['org_id']       = $orgId;
            $update['organization'] = $org?->name;
        } else {
            $update['org_id']       = null;
            $update['organization'] = null;
        }

        if ($request->hasFile('image')) {
            if ($lesson->image) \Storage::disk('public')->delete($lesson->image);
            $update['image'] = $request->file('image')->store('lesson-images', 'public');
        }

        $lesson->update($update);

        // 🔽 Persist instructor selection to a pivot (single instructor per lesson/course)
        $this->syncInstructorForLesson($lesson, $validated['instructor_id'] ?? null, $courseId);

        return redirect()->route('lesson.view', $lesson->id)
            ->with('success', 'Lesson updated successfully!');
    }

    /**
     * Sync selected instructor to whichever pivot your DB has.
     * - Prefer lesson-level pivot: instructor_lesson or lesson_instructor
     * - Else, fall back to course-level pivot: course_instructor (if course_id present)
     * Passing null will clear the assignment.
     */
    protected function syncInstructorForLesson(Lesson $lesson, ?int $instructorId, ?int $courseId): void
    {
        // lesson-level (instructor_lesson)
        if (Schema::hasTable('instructor_lesson')) {
            DB::table('instructor_lesson')->where('lesson_id', $lesson->id)->delete();
            if ($instructorId) {
                DB::table('instructor_lesson')->insert([
                    'lesson_id'     => $lesson->id,
                    'instructor_id' => $instructorId,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
            return;
        }

        // lesson-level (lesson_instructor)
        if (Schema::hasTable('lesson_instructor')) {
            DB::table('lesson_instructor')->where('lesson_id', $lesson->id)->delete();
            if ($instructorId) {
                DB::table('lesson_instructor')->insert([
                    'lesson_id'     => $lesson->id,
                    'instructor_id' => $instructorId,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
            return;
        }

        // course-level fallback
        if ($courseId && Schema::hasTable('course_instructor')) {
            DB::table('course_instructor')->where('course_id', $courseId)->delete();
            if ($instructorId) {
                DB::table('course_instructor')->insert([
                    'course_id'     => $courseId,
                    'instructor_id' => $instructorId,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
        }
    }



    public function activate(Lesson $lesson)
    {

        $lesson->update([
            'is_active' => '1',
        ]);

        return redirect()->route('lesson.index')->with('success', 'Lesson Activated!');
    }
    public function deactivate(\App\Models\Lesson $lesson)
    {
        $lesson->update([
            'is_active' => 0,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Lesson deactivated.');
    }
    public function student_register(Request $request, Lesson $lesson)
    {
        $ref = Str::upper(Str::random(10)); // correlation id for all logs

        Log::info('student_register: entered', [
            'ref'       => $ref,
            'route'     => 'lesson.student_register',
            'lesson_id' => $lesson->id,
            'method'    => $request->method(),
        ]);

        $user    = auth()->user();
        $student = $user?->student;

        if (!$user || !$student) {
            Log::warning('student_register: blocked, no student profile', [
                'ref'       => $ref,
                'user_id'   => $user?->id,
                'lesson_id' => $lesson->id,
            ]);

            return back()->with('error', 'No student record found for this user.');
        }

        // Do they already have an address on file?
        $hasAddress = $student
            && !empty($student->address_line)
            && !empty($student->city)
            && !empty($student->state)
            && !empty($student->zip);

        // Mask card number for logging
        $rawCard    = (string) $request->input('card_number', '');
        $digitsOnly = preg_replace('/\D/', '', $rawCard);
        $maskedCard = $digitsOnly
            ? substr($digitsOnly, 0, 4) . str_repeat('*', max(strlen($digitsOnly) - 4, 0))
            : null;

        Log::info('student_register: pre-validate snapshot', [
            'ref'           => $ref,
            'user_id'       => $user->id,
            'student_id'    => $student->id,
            'lesson_id'     => $lesson->id,
            'has_address'   => $hasAddress,
            'payment_method_input' => $request->input('payment_method'),
            'address_input'        => $request->only(['address', 'city', 'state', 'zip']),
            'card_meta'            => [
                'name'         => $request->input('card_name'),
                'card_masked'  => $maskedCard,
                'exp'          => $request->input('card_exp'),
                'billing_zip'  => $request->input('billing_zip'),
            ],
        ]);

        // ---- Build validation rules dynamically ----
        $rules = [
            'payment_method' => 'required|in:invoice,card',

            // Card fields optional (we’re not REALLY charging yet)
            'card_name'   => 'nullable|string|max:191',
            'card_number' => 'nullable|string|max:32',
            'card_exp'    => 'nullable|string|max:10',
            'card_cvc'    => 'nullable|string|max:4',
            'billing_zip' => 'nullable|string|max:20',
        ];

        if ($hasAddress) {
            // Address already in DB: allow these to be missing / empty
            $rules['address'] = 'nullable|string|max:255';
            $rules['city']    = 'nullable|string|max:120';
            $rules['state']   = 'nullable|string|max:10';
            $rules['zip']     = 'nullable|string|max:20';
        } else {
            // No address yet: require them
            $rules['address'] = 'required|string|max:255';
            $rules['city']    = 'required|string|max:120';
            $rules['state']   = 'required|string|max:10';
            $rules['zip']     = 'required|string|max:20';
        }

        try {
            $validated = $request->validate($rules);
            Log::info('student_register: validation passed', [
                'ref'       => $ref,
                'validated' => [
                    'payment_method' => $validated['payment_method'] ?? null,
                    'address'        => $validated['address'] ?? null,
                    'city'           => $validated['city'] ?? null,
                    'state'          => $validated['state'] ?? null,
                    'zip'            => $validated['zip'] ?? null,
                ],
            ]);
        } catch (ValidationException $e) {
            Log::warning('student_register: validation failed', [
                'ref'    => $ref,
                'errors' => $e->errors(),
            ]);
            throw $e; // let Laravel redirect back with errors
        }

        $payMethod = $validated['payment_method']; // 'invoice' or 'card'

        // ---- Update the student's physical / mailing address ----
        // Only overwrite fields that were actually sent (so we don't blank good data)
        if (array_key_exists('address', $validated) && $validated['address']) {
            $student->address_line = $validated['address'];
        }
        if (array_key_exists('city', $validated) && $validated['city']) {
            $student->city = $validated['city'];
        }
        if (array_key_exists('state', $validated) && $validated['state']) {
            $student->state = $validated['state'];
        }
        if (array_key_exists('zip', $validated) && $validated['zip']) {
            $student->zip = $validated['zip'];
        }
        $student->save();

        Log::info('student_register: student address saved/confirmed', [
            'ref'          => $ref,
            'student_id'   => $student->id,
            'address_line' => $student->address_line,
            'city'         => $student->city,
            'state'        => $student->state,
            'zip'          => $student->zip,
        ]);

        // ---- Get course fee from apps table ----
        $settings   = AppSetting::first();
        $courseCost = (float) ($lesson->student_cost ?? 0);
        $feePercent = (float) ($settings?->course_fee ?? 0); // e.g. .03 for 3%
        $feeAmount  = ($payMethod === 'card') ? $courseCost * $feePercent : 0.0;

        $subtotal = $courseCost;
        $taxOrFee = $feeAmount;          // store fee in "tax" column for now
        $total    = $subtotal + $taxOrFee;

        Log::info('student_register: pricing computed', [
            'ref'          => $ref,
            'course_cost'  => $courseCost,
            'fee_percent'  => $feePercent,
            'fee_amount'   => $feeAmount,
            'subtotal'     => $subtotal,
            'tax_or_fee'   => $taxOrFee,
            'total'        => $total,
            'pay_method'   => $payMethod,
        ]);

        $invoice = null;
        $payment = null;

        try {
            DB::transaction(function () use (
                $student,
                $lesson,
                $subtotal,
                $taxOrFee,
                $total,
                $payMethod,
                &$invoice,
                &$payment,
                $ref
            ) {
                // 1) Attach / update lesson enrollment on pivot
                $now = now();

                $student->lessons()->syncWithoutDetaching([
                    $lesson->id => [
                        'complete'          => false,
                        'access_granted'    => ($payMethod === 'card'),
                        'access_granted_at' => ($payMethod === 'card') ? $now : null,
                    ],
                ]);

                Log::info('student_register: lesson enrollment updated', [
                    'ref'             => $ref,
                    'student_id'      => $student->id,
                    'lesson_id'       => $lesson->id,
                    'access_granted'  => ($payMethod === 'card'),
                    'access_granted_at' => ($payMethod === 'card') ? $now->toDateTimeString() : null,
                ]);

                // 2) Create or fetch invoice for this student+lesson
                $invoice = Invoice::firstOrCreate(
                    [
                        'student_id' => $student->id,
                        'lesson_id'  => $lesson->id,
                    ],
                    [
                        'number'   => app(self::class)->nextInvoiceNumber(),
                        'date'     => now()->toDateString(),
                        'subtotal' => $subtotal,
                        'tax'      => $taxOrFee,
                        'total'    => $total,
                        'paid'     => ($payMethod === 'card'),
                    ]
                );

                if (!$invoice->wasRecentlyCreated) {
                    $invoice->update([
                        'subtotal' => $subtotal,
                        'tax'      => $taxOrFee,
                        'total'    => $total,
                        'paid'     => ($payMethod === 'card'),
                    ]);
                }

                Log::info('student_register: invoice ensured', [
                    'ref'        => $ref,
                    'invoice_id' => $invoice->id,
                    'paid'       => (bool) $invoice->paid,
                    'total'      => $invoice->total,
                    'method'     => $payMethod,
                ]);

                // 3) If paying by CARD, create a payment record now
                if ($payMethod === 'card') {
                    // 🔴 Adjust field names to match your payments table columns
                    $payment = Payment::create([
                        'student_id' => $student->id,
                        'lesson_id'  => $lesson->id,
                        'invoice_id' => $invoice->id ?? null,
                        'amount'     => $total,
                        'method'     => 'card',   // or appropriate column on your table
                        'state'      => 'paid',   // or 'status' => 'paid'
                    ]);

                    Log::info('student_register: payment row created', [
                        'ref'         => $ref,
                        'payment_id'  => $payment->id ?? null,
                        'invoice_id'  => $invoice->id,
                        'amount'      => $payment->amount ?? null,
                        'method'      => $payment->method ?? 'card',
                    ]);
                }
            });

            Log::info('student_register: transaction committed', [
                'ref'        => $ref,
                'invoice_id' => $invoice?->id,
                'payment_id' => $payment?->id,
            ]);

        } catch (Throwable $e) {
            Log::error('student_register: exception during transaction', [
                'ref'       => $ref,
                'user_id'   => $user->id,
                'lesson_id' => $lesson->id,
                'message'   => $e->getMessage(),
                'trace'     => $e->getTraceAsString(),
            ]);

            return back()->withErrors('Registration failed. Please try again.');
        }

        // Different message based on payment method
        $msg = $payMethod === 'invoice'
            ? 'You have been registered for this course. An invoice has been created and your access will open once payment is received.'
            : 'Your registration has been completed and payment recorded. You now have access to the course.';

        Log::info('student_register: redirecting success', [
            'ref'       => $ref,
            'user_id'   => $user->id,
            'student_id'=> $student->id,
            'lesson_id' => $lesson->id,
            'message'   => $msg,
        ]);

        return redirect()
            ->route('student.dashboard')
            ->with('success', $msg);
    }

    /**
     * Simple incremental invoice number like INV-YYYYMMDD-00001
     */
    protected function nextInvoiceNumber(): string
    {
        $date       = now()->format('Ymd');
        $countToday = Invoice::whereDate('created_at', now()->toDateString())->count() + 1;

        return sprintf('%s-%05d', $date, $countToday);
    }






}
