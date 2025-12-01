<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Certificate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class CertificateController extends Controller
{
    public function show(Lesson $lesson)
    {
        $user    = auth()->user();
        $student = $user->student;
        abort_unless($student, 403);

        $cert = $this->ensureCertificate($student->id, $lesson); // <- stable issued_at + serial

        return view('certificates.show', [
            'user'     => $user,
            'student'  => $student,
            'lesson'   => $lesson,
            'issuedAt' => $cert->issued_at,
            'serial'   => $cert->serial,
        ]);
    }

    public function download(Lesson $lesson)
    {
        $user    = auth()->user();
        $student = $user->student;
        abort_unless($student, 403);

        $cert = $this->ensureCertificate($student->id, $lesson);

        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('certificates.show', [
                'user'     => $user,
                'student'  => $student,
                'lesson'   => $lesson,
                'issuedAt' => $cert->issued_at,
                'serial'   => $cert->serial,
                'forPdf'   => true,
            ])->setPaper('letter', 'landscape');

            $filename = 'Certificate-' . str($lesson->title)->slug() . '-' . $cert->serial . '.pdf';
            return $pdf->download($filename);
        }

        return back()->with('error', 'PDF generator not installed. (composer require barryvdh/laravel-dompdf)');
    }

    /** Create once or fetch existing certificate; ensures stable issued_at + serial. */
    private function ensureCertificate(int $studentId, Lesson $lesson): Certificate
    {
        // Pivot complete?
        $pivot = DB::table('lesson_student')
            ->where('student_id', $studentId)
            ->where('lesson_id', $lesson->id)
            ->first();

        $pivotComplete = (bool)($pivot->complete ?? 0);
        $pivotWhen     = isset($pivot->updated_at) ? Carbon::parse($pivot->updated_at) : null;

        // Videos progress
        $videoIds     = collect($lesson->allVideoIds());
        $totalVideos  = $videoIds->count();
        $videosDone   = 0;
        $videosDoneAt = null;

        if ($totalVideos > 0) {
            $rows = DB::table('student_video')
                ->where('student_id', $studentId)
                ->whereIn('video_id', $videoIds)
                ->get(['completed','completed_at']);

            $videosDone   = $rows->where('completed', 1)->count();
            $videosDoneAt = $rows->where('completed', 1)->max('completed_at');
            $videosDoneAt = $videosDoneAt ? Carbon::parse($videosDoneAt) : null;
        }

        // Post-test pass (>=70)
        $postPassAt = DB::table('assessments')
            ->join('assessment_attempts','assessment_attempts.assessment_id','=','assessments.id')
            ->where('assessments.lesson_id', $lesson->id)
            ->where('assessments.type', 'post')
            ->where('assessment_attempts.student_id', $studentId)
            ->where('assessment_attempts.percent', '>=', 70)
            ->min(DB::raw('COALESCE(assessment_attempts.finished_at, assessment_attempts.completed_at, assessment_attempts.created_at)'));
        $postPassAt = $postPassAt ? Carbon::parse($postPassAt) : null;

        $autoComplete = ($totalVideos > 0 && $videosDone >= $totalVideos) && $postPassAt;

        abort_unless($pivotComplete || $autoComplete, 403);

        // Stable issue date
        $issuedAt = $pivotComplete
            ? ($pivotWhen ?? now())
            : max($videosDoneAt ?? now(), $postPassAt ?? now());

        // Persist/fetch certificate
        $cert = Certificate::firstOrCreate(
            ['student_id' => $studentId, 'lesson_id' => $lesson->id],
            ['issued_at'  => $issuedAt,   'serial'    => null]
        );

        if (!$cert->serial) {
            $cert->serial = sprintf('MEX-%s-%06d', $cert->issued_at->format('Y'), $cert->id);
            $cert->save();
        }

        return $cert->refresh();
    }
}
