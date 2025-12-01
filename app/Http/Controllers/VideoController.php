<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'video'        => 'required|file|mimetypes:video/mp4,video/quicktime,video/x-m4v,video/webm|max:512000',
            'video_length' => 'nullable|string', // client fallback (mm:ss or seconds)
            'section_id'   => 'required|exists:sections,id',
        ]);

        // 1) Save upload
        $relativeIn = $request->file('video')->store('lesson-videos/original', 'public');
        $absIn      = storage_path('app/public/'.$relativeIn);

        // 2) Probe duration (your existing ffprobe code)
        $lengthSeconds = null;
        $ffprobe = config('video.ffprobe');
        if (function_exists('shell_exec') && is_file($ffprobe) && is_executable($ffprobe)) {
            $cmd = sprintf(
                '%s -v error -show_entries format=duration -of default=nw=1:nk=1 %s 2>&1',
                escapeshellcmd($ffprobe),
                escapeshellarg($absIn)
            );
            // Log::info('FFPROBE_CMD', ['cmd' => $cmd]);
            $out = @shell_exec($cmd);
            // Log::info('FFPROBE_OUT', ['out' => $out]);
            if (is_string($out) && is_numeric(trim($out))) {
                $lengthSeconds = (int) round((float) trim($out));
            }
        }
        if ($lengthSeconds === null && $request->filled('video_length')) {
            $val = trim($request->input('video_length'));
            if (preg_match('/^(\d{1,3}):(\d{2})$/', $val, $m)) {
                $lengthSeconds = $m[1]*60 + $m[2];
            } elseif (ctype_digit($val)) {
                $lengthSeconds = (int) $val;
            }
        }
        $lengthStr = $lengthSeconds !== null
            ? sprintf('%d:%02d', intdiv($lengthSeconds,60), $lengthSeconds%60)
            : null;

        // 3) Create DB row
        $section = Section::findOrFail($validated['section_id']);

        $video = Video::create([
            'title'                 => $validated['title'],
            'video'                 => $relativeIn,          // or compressed path if you added ffmpeg step
            'video_length'          => $lengthStr,           // optional legacy string
            'video_length_seconds'  => $lengthSeconds,       // preferred numeric field
            'is_active'             => true,
            'lesson_id'             => $section->lesson_id,
        ]);

        // Attach video to section
        $section->videos()->syncWithoutDetaching([$video->id]);

        /* ========= PUT THE QUESTIONS-SAVING BLOCK RIGHT HERE ========= */
        if ($request->filled('questions')) {
            foreach ($request->input('questions') as $q) {
                if (empty($q['question'])) {
                    continue; // skip blank rows
                }

                $type = $q['type'] ?? 'multiple';

                // Normalize True/False
                if ($type === 'true_false') {
                    $q['answer_1'] = 'True';
                    $q['answer_2'] = 'False';
                    $q['answer_3'] = $q['answer_4'] = null;
                    $q['answer_3_correct'] = $q['answer_4_correct'] = false;
                }

                $question = \App\Models\Question::create([
                    'question'          => $q['question'],
                    'answer_1'          => $q['answer_1'] ?? null,
                    'answer_1_correct'  => !empty($q['answer_1_correct']),
                    'answer_2'          => $q['answer_2'] ?? null,
                    'answer_2_correct'  => !empty($q['answer_2_correct']),
                    'answer_3'          => $q['answer_3'] ?? null,
                    'answer_3_correct'  => !empty($q['answer_3_correct']),
                    'answer_4'          => $q['answer_4'] ?? null,
                    'answer_4_correct'  => !empty($q['answer_4_correct']),
                ]);

                $video->questions()->attach($question->id);
            }
        }
        /* ============================================================= */

        return back()->with('success', 'Video uploaded'.($lengthSeconds !== null ? ' (duration detected)' : '').'!');
    }


    public function update(Request $request, Video $video)
    {
        $validated = $request->validate([
            'title'         => 'required|string|max:255',
            'video'         => 'nullable|file|mimetypes:video/mp4,video/quicktime,video/x-m4v,video/webm|max:512000',
            'video_length'  => 'nullable|string',
            'questions'     => 'array|nullable',
            'questions.*.id'               => 'nullable|integer',
            'questions.*.question'         => 'required_with:questions|string',
            'questions.*.answer_1'         => 'nullable|string',
            'questions.*.answer_2'         => 'nullable|string',
            'questions.*.answer_3'         => 'nullable|string',
            'questions.*.answer_4'         => 'nullable|string',
            'questions.*.answer_1_correct' => 'nullable|boolean',
            'questions.*.answer_2_correct' => 'nullable|boolean',
            'questions.*.answer_3_correct' => 'nullable|boolean',
            'questions.*.answer_4_correct' => 'nullable|boolean',
        ]);

        $video->title = $validated['title'];

        $newLengthSeconds = null;
        $newLengthString  = null;

        // If a new video file is uploaded, replace + probe + compress
        if ($request->hasFile('video')) {
            // Store original
            $relativeIn = $request->file('video')->store('lesson-videos/original', 'public');
            $absIn      = storage_path('app/public/'.$relativeIn);

            // Probe duration (ffprobe)
            $ffprobe = config('video.ffprobe');
            if (function_exists('shell_exec') && is_file($ffprobe) && is_executable($ffprobe)) {
                $cmd = sprintf(
                    '%s -v error -show_entries format=duration -of default=nw=1:nk=1 %s 2>&1',
                    escapeshellcmd($ffprobe),
                    escapeshellarg($absIn)
                );
                Log::info('FFPROBE_CMD_UPDATE', ['cmd' => $cmd]);
                $out = @shell_exec($cmd);
                Log::info('FFPROBE_OUT_UPDATE', ['out' => $out]);

                if (is_string($out) && is_numeric(trim($out))) {
                    $newLengthSeconds = (int) round((float) trim($out));
                }
            }
            // Fallback from client hidden field if needed
            if ($newLengthSeconds === null && $request->filled('video_length')) {
                $val = trim($request->input('video_length'));
                if (preg_match('/^(\d{1,3}):(\d{2})$/', $val, $m)) {
                    $newLengthSeconds = $m[1]*60 + $m[2];
                } elseif (ctype_digit($val)) {
                    $newLengthSeconds = (int) $val;
                }
            }
            $newLengthString = $newLengthSeconds !== null
                ? sprintf('%d:%02d', intdiv($newLengthSeconds,60), $newLengthSeconds%60)
                : null;

            // Compress (ffmpeg 720p)
            $ffmpeg      = config('video.ffmpeg');
            $relativeOut = 'lesson-videos/compressed/'.Str::uuid().'.mp4';
            $absOut      = storage_path('app/public/'.$relativeOut);

            $usedCompressed = false;
            if (function_exists('shell_exec') && is_file($ffmpeg) && is_executable($ffmpeg)) {
                $encodeCmd = sprintf(
                    '%s -y -i %s -vf %s -c:v libx264 -preset medium -crf 23 -c:a aac -b:a 128k %s 2>&1',
                    escapeshellcmd($ffmpeg),
                    escapeshellarg($absIn),
                    escapeshellarg('scale=-2:720'),
                    escapeshellarg($absOut)
                );
                Log::info('FFMPEG_CMD_UPDATE', ['cmd' => $encodeCmd]);
                $encodeOut = @shell_exec($encodeCmd);
                Log::info('FFMPEG_OUT_UPDATE', ['out' => mb_substr($encodeOut ?? '', 0, 2000)]);

                if (file_exists($absOut) && filesize($absOut) > 0) {
                    $usedCompressed = true;
                    @unlink($absIn); // optional: remove original
                } else {
                    @unlink($absOut); // cleanup empty file
                }
            }

            $relativeFinal = $usedCompressed ? $relativeOut : $relativeIn;

            // Delete old stored file if it exists in public disk
            if (!empty($video->video) && Storage::disk('public')->exists($video->video)) {
                Storage::disk('public')->delete($video->video);
            }

            // Update model path + lengths
            $video->video                 = $relativeFinal;
            $video->video_length_seconds  = $newLengthSeconds ?? $video->video_length_seconds;
            $video->video_length          = $newLengthString  ?? $video->video_length;
        } else {
            // No new file: optionally accept manual length update from hidden field
            if ($request->filled('video_length')) {
                $val = trim($request->input('video_length'));
                if (preg_match('/^(\d{1,3}):(\d{2})$/', $val, $m)) {
                    $video->video_length_seconds = $m[1]*60 + $m[2];
                    $video->video_length         = sprintf('%d:%02d', $m[1], $m[2]);
                } elseif (ctype_digit($val)) {
                    $secs = (int) $val;
                    $video->video_length_seconds = $secs;
                    $video->video_length         = sprintf('%d:%02d', intdiv($secs,60), $secs%60);
                }
            }
        }

        $video->save();

        // Update / create questions (same logic you had)
        if ($request->has('questions')) {
            foreach ($request->questions as $qData) {
                if (!empty($qData['id'])) {
                    $question = \App\Models\Question::find($qData['id']);
                    if ($question) {
                        $question->update([
                            'question'           => $qData['question'],
                            'answer_1'           => $qData['answer_1'] ?? null,
                            'answer_1_correct'   => !empty($qData['answer_1_correct']),
                            'answer_2'           => $qData['answer_2'] ?? null,
                            'answer_2_correct'   => !empty($qData['answer_2_correct']),
                            'answer_3'           => $qData['answer_3'] ?? null,
                            'answer_3_correct'   => !empty($qData['answer_3_correct']),
                            'answer_4'           => $qData['answer_4'] ?? null,
                            'answer_4_correct'   => !empty($qData['answer_4_correct']),
                        ]);
                    }
                } else {
                    $question = \App\Models\Question::create([
                        'question'           => $qData['question'],
                        'answer_1'           => $qData['answer_1'] ?? null,
                        'answer_1_correct'   => !empty($qData['answer_1_correct']),
                        'answer_2'           => $qData['answer_2'] ?? null,
                        'answer_2_correct'   => !empty($qData['answer_2_correct']),
                        'answer_3'           => $qData['answer_3'] ?? null,
                        'answer_3_correct'   => !empty($qData['answer_3_correct']),
                        'answer_4'           => $qData['answer_4'] ?? null,
                        'answer_4_correct'   => !empty($qData['answer_4_correct']),
                    ]);
                    $video->questions()->attach($question->id);
                }
            }
        }

        return back()->with('success', 'Video updated'
            .($newLengthSeconds !== null ? ' (duration detected)' : '')
            .(($request->hasFile('video')) ? ' and ' . (!empty($relativeFinal) && str_starts_with($relativeFinal, 'lesson-videos/compressed/') ? 'compressed' : 'replaced') : '')
            .'.');
    }



}
