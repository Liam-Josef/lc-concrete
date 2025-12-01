<?php

namespace App\Services;

class VideoProcessor
{
    public function probeDurationSeconds(string $absPath): ?int
    {
        $ffprobe = config('video.ffprobe');
        if (!function_exists('shell_exec') || !$ffprobe || !is_file($ffprobe) || !is_executable($ffprobe)) {
            return null;
        }

        $cmd = sprintf(
            '%s -v error -show_entries format=duration -of default=nw=1:nk=1 %s 2>&1',
            escapeshellcmd($ffprobe),
            escapeshellarg($absPath)
        );

        $out = @shell_exec($cmd);
        return (is_string($out) && is_numeric(trim($out)))
            ? (int) round((float) trim($out))
            : null;
    }

    public function transcode720p(string $absIn, string $absOut): bool
    {
        $ffmpeg = config('video.ffmpeg');
        if (!function_exists('shell_exec') || !$ffmpeg || !is_file($ffmpeg) || !is_executable($ffmpeg)) {
            return false;
        }

        $cmd = sprintf(
            '%s -y -i %s -vf %s -c:v libx264 -preset medium -crf 23 -c:a aac -b:a 128k %s 2>&1',
            escapeshellcmd($ffmpeg),
            escapeshellarg($absIn),
            escapeshellarg('scale=-2:720'),
            escapeshellarg($absOut)
        );

        @shell_exec($cmd);
        return file_exists($absOut);
    }
}
