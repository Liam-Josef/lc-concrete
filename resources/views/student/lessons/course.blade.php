<x-home-fullscreen-index>

    @section('page-title')
        {{$lesson->title}} |{{$settings->app_name}}
    @endsection

    @section('Description')
        Merchants Exchange Learning Management System
    @endsection

    @section('background-image')
        {{ asset('storage/' . (($settings->internal_background ?? null) ?: 'app-images/interior-banner-1.jpg')) }}
    @endsection

    @section('content')

        <div class="white-back p-1">
            <h1 class="">{{$lesson->title}}
                <span class="small text-muted">
                <small>{{ $lesson->completed_videos_count ?? 0 }}/{{ $lesson->videos_count ?? 0 }} videos completed</small>
            </span>
            </h1>
        </div>

            @php
                $total = (int)($lesson->videos_count ?? 0);
                $done  = (int)($lesson->completed_videos_count ?? 0);
                $pct   = $total > 0 ? intval(($done / $total) * 100) : 0;
            @endphp

                <!-- Lesson Videos -->
            <div class="row mb-5">
                <div class="col-sm-8">
                    <div class="white-back">

                        @if (session('video_passed'))
                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    const f = document.getElementById('next-video-form');
                                    if (f) f.style.display = 'block';
                                });
                            </script>
                        @endif

                            @if ($currentVideo)
                                @php
                                    // Is there a next video in this lesson (using the loaded sections->videos)?
                                    $linear  = $lesson->sections->flatMap->videos->values(); // flatten & reindex
                                    $idx     = $linear->search(fn ($v) => (int)$v->id === (int)$currentVideo->id);
                                    $hasNext = $idx !== false && $linear->has($idx + 1);
                                @endphp

                                @php
                                    $isCompleted = auth()->user()->student
                                        ->videos()
                                        ->where('video_id', $currentVideo->id)
                                        ->wherePivot('completed', true)
                                        ->exists();
                                @endphp

                                <h4>{{ $currentVideo->title }}</h4>

                                <video controls width="100%">
                                    <source src="{{ Storage::url($currentVideo->video) }}" type="video/mp4">
                                </video>

                                @if (!$isCompleted)
                                    @if ($currentVideo->questions->count() > 0)
                                        <form action="{{ route('student.submitAnswers', $currentVideo->id) }}" method="POST" id="quiz-form">
                                            @csrf

                                            @foreach ($currentVideo->questions as $index => $question)
                                                <div class="mb-3 border p-3">
                                                    <strong>Question {{ $index + 1 }}:</strong> {{ $question->question }}

                                                    @for ($i = 1; $i <= 4; $i++)
                                                        @php $answer = 'answer_'.$i; @endphp
                                                        @if (!empty($question->$answer))
                                                            <div class="form-check">
                                                                <input class="form-check-input"
                                                                       type="radio"
                                                                       name="answers[{{ $question->id }}]"
                                                                       value="{{ $i }}"
                                                                       required>
                                                                <label class="form-check-label">{{ $question->$answer }}</label>
                                                            </div>
                                                        @endif
                                                    @endfor
                                                </div>
                                            @endforeach

                                        @if (session('quiz_failed'))
                                                <div class="alert alert-danger mt-3">
                                                    ❌ Some answers were incorrect. Please try again.
                                                </div>
                                            @endif

                                            <button type="submit" class="btn btn-outline-primary mt-3">Submit Answers</button>
                                        </form>
                                    @else
                                        {{-- No questions for this video: offer a "Mark as watched" to gate progress --}}
                                        <form method="POST" action="{{ route('lesson.markWatched', [$lesson->id, $currentVideo->id]) }}">
                                            @csrf
                                            <button class="btn btn-outline-primary mt-3">Mark as Watched</button>
                                        </form>
                                    @endif
                                @else
                                    <div class="alert alert-success mt-3"><i class="fa fa-check"></i> You completed this video.</div>

                                    @if ($hasNext)
                                        <a class="btn btn-primary mt-3"
                                           href="{{ route('lesson.goToNextVideo', [$lesson->id, $currentVideo->id]) }}">
                                            Next Video
                                        </a>
                                    @else
                                        <a class="btn btn-primary mt-3" href="{{ route('lessons.start', $lesson) }}">
                                            Complete
                                        </a>
                                    @endif
                                @endif
                            @else
                                {{-- No current video => playlist finished. Kick them to the smart gate. --}}
                                <div class="alert alert-info mt-3">
                                    You’ve finished all videos in this lesson.
                                </div>
                                <a class="btn btn-primary" href="{{ route('lessons.start', $lesson) }}">
                                    Continue
                                </a>
                            @endif


                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="accordion mt-3" id="accordionExample">
                        @foreach($lesson->sections as $section)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne{{$section->id}}" aria-expanded="true" aria-controls="collapseOne{{$section->id}}">
                                        {{$section->title}}
                                    </button>
                                </h2>
                                <div id="collapseOne{{$section->id}}" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <h4 class="font-primary">Section Videos</h4>
                                        @if ($section->videos->isEmpty())
                                            <p>No videos in this section.</p>
                                        @else
                                            @foreach ($section->videos as $video)
                                                <a href="{{ route('lesson.showVideo', [$lesson->id, $video->id]) }}" class="text-decoration-none">
                                                    <div class="row">
                                                        <div class="col-12">

                                                                <strong>{{ $video->title }}</strong>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="mb-3">
                                                                <video controls width="100%">
                                                                    <source src="{{ Storage::url($video->video) }}" type="video/mp4">
                                                                    Your browser does not support the video tag.
                                                                </video>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <p class="text-muted">
                                                                Length: {{ $video->video_length }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </a>
                                                <hr>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>

                        @endforeach


                    </div>
                </div>
            </div>
        <!-- /Lesson Videos -->

    @endsection

</x-home-fullscreen-index>
