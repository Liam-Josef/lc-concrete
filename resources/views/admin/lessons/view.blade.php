<x-admin-master>

    @section('page-title')
        Course: {{$lesson->title}} | MEX LMS Admin
    @endsection

    @section('styles')

    @endsection

    @section('content')
        <div class="row">
            <div class="col-sm-7">
                <h1 class="m-3">{{$lesson->title}}</h1>
            </div>
            <div class="col-sm-5">
                <a href="{{route('lesson.edit', $lesson->id)}}" class="btn btn-primary btn-right">Update Course</a>
            </div>
        </div>
            @php
                // Count videos across all sections
                $videoCount = $lesson->sections->sum(fn ($s) => $s->videos->count());

                // Sum durations from $video->video_length ("MM:SS" or "H:MM:SS")
                $totalSeconds = $lesson->sections->flatMap->videos->reduce(function ($sum, $v) {
                    $len = (string) ($v->video_length ?? '');
                    if ($len === '') return $sum;

                    $parts = array_map('intval', explode(':', $len)); // ["MM","SS"] or ["H","MM","SS"]
                    if (count($parts) === 3) {
                        [$h,$m,$s] = $parts;
                        return $sum + ($h * 3600) + ($m * 60) + $s;
                    } elseif (count($parts) === 2) {
                        [$m,$s] = $parts;
                        return $sum + ($m * 60) + $s;
                    }
                    return $sum;
                }, 0);

                $h = intdiv($totalSeconds, 3600);
                $m = intdiv($totalSeconds % 3600, 60);
                $s = $totalSeconds % 60;
                $totalFormatted = $totalSeconds > 0
                    ? ($h > 0 ? sprintf('%d:%02d:%02d', $h, $m, $s) : sprintf('%d:%02d', $m, $s))
                    : '0:00';
            @endphp
            @php
                $questionsTotal = $lesson->sections
                    ->flatMap->videos
                    ->sum(fn($v) => $v->questions->count());
            @endphp
            <div class="row background-white">
                <div class="col-2 text-center">
                    <span class="text-primary"><b>CEU:</b></span> {{$lesson->total_ceu}}
                </div>
                <div class="col-2 text-center">
                    <span class="text-primary"><b>Hours:</b></span> {{$lesson->total_hours}} ({{ $totalFormatted }})
                </div>
                <div class="col-2 text-center">
                    <span class="text-primary"><b>Cost:</b></span> ${{$lesson->student_cost}}
                </div>
                <div class="col-2 text-center">
                    @if($lesson->event_link != '')
                        <a href="{{$lesson->event_link}}" target="_blank" class="disabled"> <b>Event Link</b> </a>
                    @else
                       <span class="text-gray-700"> No Event Link </span>
                    @endif
                </div>
                <div class="col-2 text-center">
                    <span class="text-primary"><b>Videos:</b></span> {{ $videoCount }}
                </div>
                <div class="col-2 text-center">
                    <span class="text-primary"><b>Questions:</b></span> {{ $questionsTotal }}
                </div>
            </div>


        <div class="card shadow mb-4">
            @if ($errors->any())
                <div class="card-header">
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <img src="{{ asset('storage/' . $lesson->image) }}" class="img-responsive img-250h-center" alt="{{$lesson->title}}" title="{{$lesson->title}}"/>
                    </div>
                    <div class="col-sm-6">
                        <h4 class="text-primary">Short Description</h4>
                        <p>{{$lesson->short_description}}</p>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-12">
                        <!-- Tabs -->
                        <ul class="nav nav-tabs justify-content-center" id="courseTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active"
                                        id="tab-zero"
                                        data-bs-toggle="tab"
                                        data-bs-target="#pane-zero"
                                        type="button" role="tab"
                                        aria-controls="pane-zero"
                                        aria-selected="true">
                                    Long Description
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link"
                                        id="tab-one"
                                        data-bs-toggle="tab"
                                        data-bs-target="#pane-one"
                                        type="button" role="tab"
                                        aria-controls="pane-one"
                                        aria-selected="true">
                                    Learning Outcomes
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link"
                                        id="tab-two"
                                        data-bs-toggle="tab"
                                        data-bs-target="#pane-two"
                                        type="button" role="tab"
                                        aria-controls="pane-two"
                                        aria-selected="false">
                                    Course Notes
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link"
                                        id="tab-three"
                                        data-bs-toggle="tab"
                                        data-bs-target="#pane-three"
                                        type="button" role="tab"
                                        aria-controls="pane-three"
                                        aria-selected="false">
                                    Completion Requirements
                                </button>
                            </li>
                            {{--                                <li class="nav-item" role="presentation">--}}
                            {{--                                    <button class="nav-link disabled"--}}
                            {{--                                            id="tab-disabled"--}}
                            {{--                                            type="button" role="tab"--}}
                            {{--                                            aria-disabled="true">--}}
                            {{--                                        Disabled--}}
                            {{--                                    </button>--}}
                            {{--                                </li>--}}
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content pt-3" id="courseTabsContent">
                            <div class="tab-pane fade show active"
                                 id="pane-zero"
                                 role="tabpanel"
                                 aria-labelledby="tab-zero"
                                 tabindex="0">
                                <h4 class="text-primary mb-0">Long Description</h4>
                                <div class="preserve-lines">
                                    {{ $lesson->long_description }}
                                </div>
                            </div>
                            <div class="tab-pane fade show"
                                 id="pane-one"
                                 role="tabpanel"
                                 aria-labelledby="tab-one"
                                 tabindex="0">
                                <h4 class="text-primary mb-0">Learning Outcomes</h4>
                                <div class="preserve-lines">
                                    {{ $lesson->learning_outcomes }}
                                </div>
                            </div>

                            <div class="tab-pane fade"
                                 id="pane-two"
                                 role="tabpanel"
                                 aria-labelledby="tab-two"
                                 tabindex="0">
                                <h4 class="text-primary mb-0">Course Notes</h4>
                                <div class="preserve-lines">
                                    {{ $lesson->course_notes }}
                                </div>
                            </div>

                            <div class="tab-pane fade"
                                 id="pane-three"
                                 role="tabpanel"
                                 aria-labelledby="tab-three"
                                 tabindex="0">
                                <h4 class="text-primary mb-0">Completion Requirements</h4>
                                <div class="preserve-lines">
                                    {{ $lesson->completion_requirements }}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <hr class="mt-5 mb-5">

                <div class="row mt-5">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="text-primary">Sections</h3>
                            <form action="{{ route('sections.store', $lesson->id) }}" method="POST">
                                @csrf

                                <div class="form-group">
                                    <label for="title">Section Title</label>
                                    <input type="text" class="form-control" name="title" required>
                                    <button class="btn btn-primary mt-3" type="submit">Submit</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h3 class="text-primary">Section Videos</h3>
                                    @if($lesson->sections->isEmpty())
                                        <p>No sections attached to this Course.</p>
                                    @else
                                </div>
{{--                                <div class="col-sm-6">--}}
{{--                                    <form method="POST" action="{{ route('assessments.rebuild',$lesson) }}">--}}
{{--                                        @csrf--}}
{{--                                        <button class="btn btn-outline-primary">Refresh Questions</button>--}}
{{--                                    </form>--}}
{{--                                </div>--}}
                            </div>


                                <div class="accordion" id="accordionExample">
                                    @foreach($lesson->sections as $section)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingOne">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne{{$section->id}}" aria-expanded="true" aria-controls="collapseOne{{$section->id}}">
                                                    Section: {{$section->title}}
                                                </button>
                                            </h2>
                                            <div id="collapseOne{{$section->id}}" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    @if ($section->videos->isEmpty())
                                                        <p>No videos in this section.</p>
                                                    @else
                                                        @foreach ($section->videos as $video)
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <div class="mb-3">
                                                                        <strong>{{ $video->title }}</strong>
                                                                        <video controls width="100%">
                                                                            <source src="{{ Storage::url($video->video) }}" type="video/mp4">
                                                                            Your browser does not support the video tag.
                                                                        </video>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6">
                                                                    <p class="text-muted">
                                                                        Length: {{ $video->video_length_formatted ?? '—' }}
                                                                        <br>
                                                                        {{ $video->questions->count() }} {{ Str::plural('Question', $video->questions->count()) }}
                                                                    </p>
                                                                    <button class="btn btn-small btn-outline-primary mt-1 btn-right btn-100" data-bs-toggle="modal" data-bs-target="#editVideoModal{{ $video->id }}">
                                                                        Edit Video
                                                                    </button>
                                                                </div>
                                                            </div>


                                                            <hr>
                                                            <div class="modal fade" id="editVideoModal{{ $video->id }}" tabindex="-1" aria-labelledby="editVideoModalLabel{{ $video->id }}" aria-hidden="true">
                                                                <div class="modal-dialog modal-lg">
                                                                    <div class="modal-content">
                                                                        <form action="{{ route('videos.update', $video->id) }}" method="POST" enctype="multipart/form-data">
                                                                            @csrf
                                                                            @method('PUT')

                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="editVideoModalLabel{{ $video->id }}">Edit Video: {{ $video->title }}</h5>
                                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                            </div>

                                                                            <div class="modal-body">
                                                                                <input type="hidden" name="section_id" value="{{ $section->id }}">
                                                                                <div class="mb-3">
                                                                                    <label>Video Title</label>
                                                                                    <input type="text" name="title" class="form-control" value="{{ $video->title }}" required>
                                                                                </div>

                                                                                <div class="mb-3">
                                                                                    <label>Replace Video (optional)</label>
                                                                                    <input type="file"
                                                                                           name="video"
                                                                                           class="form-control video-input"
                                                                                           accept="video/mp4,video/webm,video/quicktime,video/x-m4v"
                                                                                           id="videoInput-edit-{{ $video->id }}">

                                                                                    <input type="hidden"
                                                                                           name="video_length"
                                                                                           id="videoLength-edit-{{ $video->id }}">

                                                                                    <small class="text-muted">Leave blank to keep existing video</small>
                                                                                </div>


                                                                                <div id="edit-questions-container-{{ $video->id }}" class="mb-3">
                                                                                    @foreach ($video->questions as $index => $question)
                                                                                        @php
                                                                                            $isTrueFalse =
                                                                                                strtolower($question->answer_1) === 'true' &&
                                                                                                strtolower($question->answer_2) === 'false' &&
                                                                                                empty($question->answer_3) &&
                                                                                                empty($question->answer_4);
                                                                                        @endphp

                                                                                        <div class="border p-3 mb-3 question-block">
                                                                                            <h5>Question {{ $index + 1 }}</h5>
                                                                                            <input type="hidden" name="questions[{{ $index }}][id]" value="{{ $question->id }}">

                                                                                            <div class="mb-2">
                                                                                                <label>Question</label>
                                                                                                <input type="text"
                                                                                                       name="questions[{{ $index }}][question]"
                                                                                                       value="{{ $question->question }}"
                                                                                                       class="form-control"
                                                                                                       required>
                                                                                            </div>

                                                                                            @php
                                                                                                $isTrueFalse =
                                                                                                  strtolower($question->answer_1) === 'true' &&
                                                                                                  strtolower($question->answer_2) === 'false' &&
                                                                                                  empty($question->answer_3) &&
                                                                                                  empty($question->answer_4);
                                                                                                $answersId = "answers-{$video->id}-{$index}";
                                                                                            @endphp

                                                                                            <div class="mb-2">
                                                                                                <label>Question Type</label>
                                                                                                <select name="questions[{{ $index }}][type]"
                                                                                                        class="form-select question-type"
                                                                                                        data-target="{{ $answersId }}"
                                                                                                        data-question="{{ $index }}">
                                                                                                    <option value="multiple" {{ !$isTrueFalse ? 'selected' : '' }}>Multiple Choice</option>
                                                                                                    <option value="true_false" {{ $isTrueFalse ? 'selected' : '' }}>True / False</option>
                                                                                                </select>
                                                                                            </div>

                                                                                            <div class="answer-fields" id="{{ $answersId }}">
                                                                                                @if ($isTrueFalse)
                                                                                                    <div class="mb-2">
                                                                                                        <label>Answer 1 (True)</label>
                                                                                                        <input type="text" name="questions[{{ $index }}][answer_1]" value="True" class="form-control" readonly>
                                                                                                        <div class="form-check">
                                                                                                            <input type="checkbox" class="form-check-input" name="questions[{{ $index }}][answer_1_correct]" value="1" {{ $question->answer_1_correct ? 'checked' : '' }}>
                                                                                                            <label class="form-check-label">Mark as correct</label>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="mb-2">
                                                                                                        <label>Answer 2 (False)</label>
                                                                                                        <input type="text" name="questions[{{ $index }}][answer_2]" value="False" class="form-control" readonly>
                                                                                                        <div class="form-check">
                                                                                                            <input type="checkbox" class="form-check-input" name="questions[{{ $index }}][answer_2_correct]" value="1" {{ $question->answer_2_correct ? 'checked' : '' }}>
                                                                                                            <label class="form-check-label">Mark as correct</label>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                @else
                                                                                                    @for ($i = 1; $i <= 4; $i++)
                                                                                                        @php
                                                                                                            $answer = 'answer_' . $i;
                                                                                                            $correct = 'answer_' . $i . '_correct';
                                                                                                        @endphp
                                                                                                        @if ($question->$answer || $i <= 2)
                                                                                                            <div class="mb-2">
                                                                                                                <label>Answer {{ $i }}</label>
                                                                                                                <input type="text"
                                                                                                                       name="questions[{{ $index }}][{{ $answer }}]"
                                                                                                                       value="{{ $question->$answer }}"
                                                                                                                       class="form-control"
                                                                                                                    {{ $i <= 2 ? 'required' : '' }}>
                                                                                                                <div class="form-check">
                                                                                                                    <input type="checkbox" class="form-check-input"
                                                                                                                           name="questions[{{ $index }}][{{ $correct }}]"
                                                                                                                           value="1" {{ $question->$correct ? 'checked' : '' }}>
                                                                                                                    <label class="form-check-label">Mark as correct</label>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        @endif
                                                                                                    @endfor
                                                                                                @endif
                                                                                            </div>
                                                                                        </div>
                                                                                    @endforeach
                                                                                </div>

                                                                                <!-- Add Question Button -->
                                                                                <button type="button"
                                                                                        class="btn btn-outline-secondary mb-3 ml-3 add-question-btn"
                                                                                        data-video-id="{{ $video->id }}">
                                                                                    + Add Question
                                                                                </button>

                                                                            </div>

                                                                            <div class="modal-footer">
                                                                                <button type="submit" class="btn btn-primary">Update Video</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @endif

                                                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVideoModal{{ $section->id }}">
                                                        Add Video
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Add Video Modal -->
                                        <div class="modal fade" id="addVideoModal{{ $section->id }}" tabindex="-1" aria-labelledby="addVideoModalLabel{{ $section->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('videos.store') }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        <input type="hidden" name="section_id" value="{{ $section->id }}">

                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Add Video to {{ $section->title }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label>Video Title</label>
                                                                <input type="text" name="title" class="form-control" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label>Video File</label>
                                                                <input type="file"
                                                                       name="video"
                                                                       class="form-control video-input"
                                                                       accept="video/mp4"
                                                                       id="videoInput-add-{{ $section->id }}">

                                                                <input type="hidden"
                                                                       name="video_length"
                                                                       id="videoLength-add-{{ $section->id }}"
                                                                       value="{{ isset($video) ? $video->video_length : '' }}">

                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-12">
                                                                <h3 class="text-primary">Video Questions</h3>
                                                            </div>
                                                        </div>
                                                        <div id="questions-container-{{ $section->id }}" class="mb-3"></div>

                                                        <button type="button"
                                                                class="btn btn-outline-secondary mb-3 ml-3 add-question-btn"
                                                                data-section-id="{{ $section->id }}">
                                                            + Add Question
                                                        </button>

                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary">Upload</button>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                        <!-- /Add Video Modal -->

                                    @endforeach


                                </div>

                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>


    @endsection

    @section('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    document.querySelectorAll('.video-input').forEach(input => {
                        input.addEventListener('change', function () {
                            // Accept both "videoInput-edit-123" → "videoLength-edit-123"
                            // and "videoInput-add-45"        → "videoLength-add-45"
                            const id = this.id;
                            let hiddenId = null;

                            if (id.startsWith('videoInput-')) {
                                hiddenId = id.replace('videoInput-', 'videoLength-');
                            } else if (id.startsWith('videoInput')) {
                                // fallback: if someone used "videoInputXYZ"
                                hiddenId = id.replace('videoInput', 'videoLength');
                            }

                            const hidden = hiddenId ? document.getElementById(hiddenId) : null;
                            const file   = this.files?.[0];
                            if (!hidden || !file) return;

                            const v = document.createElement('video');
                            v.preload = 'metadata';
                            v.onloadedmetadata = function () {
                                URL.revokeObjectURL(v.src);
                                const d = v.duration || 0;
                                const m = Math.floor(d / 60);
                                const s = String(Math.floor(d % 60)).padStart(2,'0');
                                hidden.value = `${m}:${s}`;
                            };
                            v.src = URL.createObjectURL(file);
                        });
                    });
                });
            </script>

            <script>
                // ---------- Renderers ----------
                function renderMultipleChoiceInputs(qNum) {
                    return [1,2,3,4].map(i => `
    <div class="mb-2">
      <label>Answer ${i}</label>
      <input type="text" name="questions[${qNum}][answer_${i}]" class="form-control" ${i<=2 ? 'required' : ''}>
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="questions[${qNum}][answer_${i}_correct]" value="1">
        <label class="form-check-label">Mark as correct</label>
      </div>
    </div>
  `).join('');
                }
                function renderTrueFalseInputs(qNum) {
                    return `
    <div class="mb-2">
      <label>Answer 1 (True)</label>
      <input type="text" name="questions[${qNum}][answer_1]" value="True" class="form-control" readonly>
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="questions[${qNum}][answer_1_correct]" value="1">
        <label class="form-check-label">Mark as correct</label>
      </div>
    </div>
    <div class="mb-2">
      <label>Answer 2 (False)</label>
      <input type="text" name="questions[${qNum}][answer_2]" value="False" class="form-control" readonly>
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="questions[${qNum}][answer_2_correct]" value="1">
        <label class="form-check-label">Mark as correct</label>
      </div>
    </div>
  `;
                }

                // ---------- Global change handler for type select ----------
                document.addEventListener('change', function (e) {
                    if (!e.target.classList.contains('question-type')) return;

                    const idx = e.target.dataset.question;
                    const targetId = e.target.dataset.target;
                    if (!targetId) return;

                    const target = document.getElementById(targetId);
                    if (!target) return;

                    target.innerHTML = (e.target.value === 'true_false')
                        ? renderTrueFalseInputs(idx)
                        : renderMultipleChoiceInputs(idx);
                });

                // ---------- Add Video modal (per section) ----------
                document.addEventListener('click', function (e) {
                    if (!e.target.classList.contains('add-question-btn')) return;

                    const sectionId = e.target.dataset.sectionId;
                    if (!sectionId) return; // not an "add" modal button

                    const container = document.getElementById('questions-container-' + sectionId);
                    if (!container) return;

                    const index = container.querySelectorAll('.question-block').length;
                    const answersId = `answers-add-${sectionId}-${index}`;

                    const block = document.createElement('div');
                    block.className = 'border p-3 mb-3 question-block';
                    block.innerHTML = `
    <h5>Question ${index + 1}</h5>
    <div class="mb-2">
      <label>Question</label>
      <input type="text" name="questions[${index}][question]" class="form-control" required>
    </div>

    <div class="mb-2">
      <label>Question Type</label>
      <select name="questions[${index}][type]" class="form-select question-type"
              data-question="${index}" data-target="${answersId}">
        <option value="multiple">Multiple Choice</option>
        <option value="true_false">True / False</option>
      </select>
    </div>

    <div class="answer-fields" id="${answersId}">
      ${renderMultipleChoiceInputs(index)}
    </div>
  `;
                    container.appendChild(block);
                });

                // ---------- Edit Video modal (per video) ----------
                document.addEventListener('click', function (e) {
                    if (!e.target.classList.contains('add-question-btn')) return;

                    const videoId = e.target.dataset.videoId;
                    if (!videoId) return; // handled by the "add" modal code above

                    const container = document.getElementById('edit-questions-container-' + videoId);
                    if (!container) return;

                    const index = container.querySelectorAll('.question-block').length;
                    const answersId = `answers-${videoId}-${index}`;

                    const block = document.createElement('div');
                    block.className = 'border p-3 mb-3 question-block';
                    block.innerHTML = `
    <h5>Question ${index + 1}</h5>
    <div class="mb-2">
      <label>Question</label>
      <input type="text" name="questions[${index}][question]" class="form-control" required>
    </div>

    <div class="mb-2">
      <label>Question Type</label>
      <select name="questions[${index}][type]" class="form-select question-type"
              data-question="${index}" data-target="${answersId}">
        <option value="multiple">Multiple Choice</option>
        <option value="true_false">True / False</option>
      </select>
    </div>

    <div class="answer-fields" id="${answersId}">
      ${renderMultipleChoiceInputs(index)}
    </div>
  `;
                    container.appendChild(block);
                });
            </script>





        @endsection

</x-admin-master>
