<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    public function user_profile(User $user)
    {
        $student = $user->student;

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

        // AFTER you computed $progress
        $eligibleMap = [];
        $issuedAtMap = [];

        if ($student && $lessons->isNotEmpty()) {
            foreach ($lessons as $l) {
                // pivot complete?
                $pivotComplete = (int)optional($l->pivot)->complete === 1;

                // post-assessment "pass" = percent >= 70; take the best timestamp we have
                $postPassedAt = DB::table('assessments')
                    ->join('assessment_attempts', 'assessment_attempts.assessment_id', '=', 'assessments.id')
                    ->where('assessments.lesson_id', $l->id)
                    ->where('assessments.type', 'post')
                    ->where('assessment_attempts.student_id', $student->id)
                    ->where('assessment_attempts.percent', '>=', 70)
                    ->selectRaw('MAX(COALESCE(assessment_attempts.finished_at, assessment_attempts.completed_at, assessment_attempts.created_at)) as passed_at')
                    ->value('passed_at');

                // videos completed?
                $p = $progress[$l->id] ?? ['completed'=>0,'total'=>0];
                $videosAllDone = ($p['total'] ?? 0) > 0 && ($p['completed'] ?? 0) >= ($p['total'] ?? 0);

                // eligible if pivot complete OR (all videos done AND post passed)
                $eligible = $pivotComplete || ($videosAllDone && !is_null($postPassedAt));

                $eligibleMap[$l->id] = $eligible;
                $issuedAtMap[$l->id] = $eligible ? ($postPassedAt ?: now()) : null;

                // (Optional) persist completion so future loads don't recompute:
                // if (!$pivotComplete && $eligible) {
                //     DB::table('lesson_student')
                //       ->where('lesson_id', $l->id)
                //       ->where('student_id', $student->id)
                //       ->update(['complete' => 1, 'updated_at' => now()]);
                // }
            }
        }


        $completed  = $student ? $student->lessons()->wherePivot('complete', true)->get()  : collect();
        $inProgress = $student ? $student->lessons()->wherePivot('complete', false)->get() : collect();

        return view('admin.utilities.users.profile', compact('user', 'progress', 'student', 'lessons', 'completed', 'inProgress', 'eligibleMap', 'issuedAtMap'));

    }


    public function profile(User $user)
    {
        $student = $user->student;

        $lessons = $student
            ? $student->lessons()
                ->with('course')
                ->orderBy('lesson_student.created_at','desc')
                ->get()
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

        $eligibleMap = [];
        $issuedAtMap = [];

        if ($student && $lessons->isNotEmpty()) {
            foreach ($lessons as $l) {
                $pivotComplete = (int) optional($l->pivot)->complete === 1;

                $postPassedAt = DB::table('assessments')
                    ->join('assessment_attempts', 'assessment_attempts.assessment_id', '=', 'assessments.id')
                    ->where('assessments.lesson_id', $l->id)
                    ->where('assessments.type', 'post')
                    ->where('assessment_attempts.student_id', $student->id)
                    ->where('assessment_attempts.percent', '>=', 70)
                    ->selectRaw('MAX(COALESCE(assessment_attempts.finished_at, assessment_attempts.completed_at, assessment_attempts.created_at)) as passed_at')
                    ->value('passed_at');

                $p = $progress[$l->id] ?? ['completed' => 0, 'total' => 0];
                $videosAllDone = ($p['total'] ?? 0) > 0 && ($p['completed'] ?? 0) >= ($p['total'] ?? 0);

                $eligible = $pivotComplete || ($videosAllDone && !is_null($postPassedAt));

                $eligibleMap[$l->id] = $eligible;
                $issuedAtMap[$l->id] = $eligible ? ($postPassedAt ?: now()) : null;
            }
        }

        return view('user.profile', [
            'user'        => $user,
            'student'     => $student,
            'lessons'     => $lessons,
            'progress'    => $progress,
            'eligibleMap' => $eligibleMap,
            'issuedAtMap' => $issuedAtMap,
        ]);
    }


    public function account(User $user) {
        $user = auth()->user();
        $student = $user->student;

        $invoices = $student
            ? $student->invoices()->latest()->get()
            : collect();

        return view('user.account', [
            'user' => $user,
            'invoices' => $invoices,
        ]);
    }
    public function settings(User $user) {
        return view('user.settings', [
            'user' => $user,
        ]);
    }

    public function update_info(User $user) {

    }


    public function showInvoice(Invoice $invoice)
    {
        $user    = auth()->user();
        $student = $user->student;

        // Security: only allow the owner of the invoice to see it
        if (!$student || (int) $invoice->student_id !== (int) $student->id) {
            abort(403);
        }

        $invoice->loadMissing(['lesson', 'payments']); // in case you have these relations
        $app = AppSetting::first();                   // for logo, name, etc.

        return view('user.billing.invoice', [
            'invoice' => $invoice,
            'student' => $student,
            'user'    => $user,
            'app'     => $app,
        ]);
    }


}
