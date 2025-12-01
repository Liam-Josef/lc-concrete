<x-home-fullscreen-master>


    @section('page-title') Progress — {{ $lesson->title }} | {{$settings->app_name}}@endsection

    @section('description')
        Merchants Exchange Learning Management System
    @endsection

    @section('background-image')
        {{ asset('storage/' . (($settings->internal_background ?? null) ?: 'app-images/interior-banner-1.jpg')) }}
    @endsection

    @section('banner')
        <img src="{{asset('storage/app-images/interior-banner.jpg')}}" class="img-responsive" alt="MEX Learning Banner" title="MEX Learning Banner"/>
    @endsection

    @section('scripts')

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const modal    = document.getElementById('certificateModal');
                    const frame    = document.getElementById('certFrame');
                    const spinner  = document.getElementById('certSpinner');
                    const printBtn = document.getElementById('certPrintBtn');
                    const certUrl  = @json(route('certificates.show', $lesson));

                    // Bind once, before any src is set
                    if (!frame.dataset.loadBound) {
                        frame.addEventListener('load', () => {
                            // hide spinner when the iframe finishes loading
                            spinner.style.display = 'none';
                            frame.dataset.loaded = '1';
                        });
                        frame.dataset.loadBound = '1';
                    }

                    // When modal is shown, (re)show spinner and ensure src is set
                    modal.addEventListener('shown.bs.modal', () => {
                        spinner.style.display = 'flex';           // show while loading
                        if (frame.dataset.loaded !== '1') {
                            frame.src = certUrl;                    // first load
                        } else {
                            // already loaded (e.g., reopening modal) -> hide spinner immediately
                            spinner.style.display = 'none';
                        }
                    });

                    // Optional: when modal hides, keep the content but show spinner next time
                    modal.addEventListener('hide.bs.modal', () => {
                        spinner.style.display = 'none';
                    });

                    // Print the iframe content
                    printBtn.addEventListener('click', () => {
                        if (frame && frame.contentWindow) {
                            frame.contentWindow.focus();
                            frame.contentWindow.print();
                        }
                    });
                });
            </script>


        @endsection

    @section('content')
    <div class="card mb-3 mt-5">
        <div class="card-header"><h4 class="m-0">Your Progress: {{ $lesson->title }}</h4></div>
        <div class="card-body">
            <div class="row text-center">
                <div class="col-md-4">
                    <h6>Pre-Test</h6>
                    <div class="display-6">{{ $lastPre?->percent ?? '—' }}%</div>
                    @if($lastPre)<small>{{ $lastPre->score }}/{{ $lastPre->total }} correct</small>@endif
                </div>
                <div class="col-md-4">
                    <h6>Post-Test</h6>
                    <div class="display-6">{{ $lastPost?->percent ?? '—' }}%</div>
                    @if($lastPost)<small>{{ $lastPost->score }}/{{ $lastPost->total }} correct</small>@endif
                </div>
                <div class="col-md-4">
                    <h6>Improvement</h6>
                    <div class="display-6">{{ !is_null($delta) ? ($delta >= 0 ? '+' : '') . $delta : '—' }} pts</div>
                </div>
            </div>
        </div>
    </div>

            @php
                $percentPost = $lastPost?->percent;
                $passed = !is_null($percentPost) && $percentPost >= 70; // threshold
            @endphp

            {{-- Outcome actions --}}
            @if ($lastPost)
                @if ($passed)
                    <div class="alert alert-success mt-4">
                        🎉 <strong>Congratulations!</strong> You passed the post-test with {{ number_format($percentPost) }}%!
                    </div>

                    <div class="white-back">
                        <div class="d-flex flex-wrap gap-2">
                            <button class="btn btn-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#certificateModal">
                                View Certificate
                            </button>

                            <a class="btn btn-secondary"
                               href="{{ route('student.course_lesson', $lesson->id) }}">
                                Re-watch Videos
                            </a>

                            <a class="btn btn-outline-primary"
                               href="{{ route('student.dashboard') }}">
                                Back to Dashboard
                            </a>
                        </div>
                    </div>

                @else
                    <div class="alert alert-warning mt-4">
                        <strong>Almost there.</strong> You scored {{ number_format($percentPost, 2) }}%.
                        You need at least <strong>70%</strong> to pass.
                    </div>

                    <div class="white-back">
                        <div class="d-flex flex-wrap gap-2">
                            <a class="btn btn-primary"
                               href="{{ route('assessments.show', [$lesson->id, 'post']) }}">
                                Re-take Post-Test
                            </a>

                            <a class="btn btn-secondary"
                               href="{{ route('student.course_lesson', $lesson->id) }}">
                                Re-watch Videos
                            </a>

                            <a class="btn btn-outline-primary"
                               href="{{ route('student.dashboard') }}">
                                Back to Dashboard
                            </a>
                        </div>
                    </div>
                @endif
            @else
                {{-- Fallback: no post attempt yet (shouldn't happen if you only land here after POST) --}}
                <div class="alert alert-info mt-4">
                    You haven’t taken the post-test yet.
                </div>
                <a class="btn btn-primary"
                   href="{{ route('assessments.show', [$lesson->id, 'post']) }}">
                    Take Post-Test
                </a>
            @endif


            <!-- Certificate Modal -->
            <div class="modal fade" id="certificateModal" tabindex="-1" aria-labelledby="certificateModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="certificateModalLabel">Certificate: {{ $lesson->title }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body p-0">
                            <iframe id="certFrame"
                                    src="{{ route('certificates.show', $lesson) }}"
                                    class="w-100 border-0"
                                    style="height:72vh;"
                                    loading="lazy"></iframe>
                        </div>

                        <div class="modal-footer">
{{--                            <a class="btn btn-primary" href="{{ route('certificates.download', $lesson) }}">Download PDF</a>--}}
                            <button type="button" class="btn btn-secondary"
                                    onclick="document.getElementById('certFrame').contentWindow.print()">Print</button>
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>




        @endsection



</x-home-fullscreen-master>
