<x-home-fullscreen-index>
    @section('page-title') Courses | {{$settings->app_name}} @endsection
    @section('description') Merchants Exchange Learning Management System @endsection
        @section('background-image')
            {{ asset('storage/' . (($settings->internal_background ?? null) ?: 'app-images/interior-banner-1.jpg')) }}
        @endsection
    @section('banner')
        <img src="{{asset('storage/app-images/interior-banner.jpg')}}" class="img-responsive" alt="MEX Learning Banner" title="MEX Learning Banner"/>
    @endsection

    @section('style')

            <style type="text/css">
                /* Smooth image resize */
                .card-img-top {
                    max-height: 220px;          /* your “normal” image height */
                    object-fit: cover;
                    transition: max-height 0.2s ease;
                }

                /* When description is open, shrink image to ~50% */
                .card-img-top.card-img-collapsed {
                    max-height: 110px;
                }

                /* Description hidden by default */
                .desc-text {
                    display: none;
                    font-size: 0.9rem;
                }

                /* Optional: slightly tighter spacing when open */
                .card.desc-open .card-body {
                    padding-bottom: 0.75rem;
                }

                /* Description is collapsed & invisible by default */
                .desc-text {
                    max-height: 0;
                    opacity: 0;
                    overflow: hidden;
                    transition: opacity 1s ease, max-height 1s ease;
                }

                /* When the card is "open", animate it in */
                .card.desc-open .desc-text {
                    max-height: 200px; /* adjust depending on how tall your text can be */
                    opacity: 1;
                }

            </style>
    @endsection

    @section('content')

        <!-- Title -->
        <div class="white-back mt-4">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="text-primary mb-0">Courses</h1>
                </div>

            </div>
        </div>
        <!-- /Title -->

        <div class="white-back">

            @if($shippingCourse)
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h3>{{ $shippingCourse->title }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="row">
                                        @forelse($shippingCourse->lessons as $lesson)
                                            <div class="col-6 mb-3">
                                                <a href="{{ route('home.course_lesson', $lesson->id) }}" class="text-decoration-none">
                                                    @if ($lesson->image)
                                                        <img
                                                            src="{{ Storage::url($lesson->image) }}"
                                                            class="img-fluid mb-2 img-responsive"
                                                            alt="{{ $lesson->title }}"
                                                            title="{{ $lesson->title }}"
                                                        />
                                                    @endif
                                                </a>
                                            </div>
                                        @empty
                                            <div class="col-12 text-muted">No lessons yet.</div>
                                        @endforelse
                                    </div>
                                </div>

                                <div class="col-sm-6">

                                    <p class="card-text mb-0">
                                        {{ $shippingCourse->short_description
                                           ?: \Illuminate\Support\Str::limit($shippingCourse->long_description, 160)
                                           ?: 'No description yet.' }}
                                    </p>

                                    <a href="{{ route('home.courses.show', $shippingCourse) }}"
                                       class="btn btn-primary btn-100 mt-3">
                                        Learn More
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif


        </div>


            @php
                $hasCards = ($otherCourses ?? collect())->isNotEmpty()
                         || ($standaloneLessons ?? collect())->isNotEmpty();
            @endphp

            @if($hasCards)
                <div class="white-back mt-4">
                    <div class="col-sm-12">
                        <h3 class="mb-3">Courses</h3>

                        <div class="row">

                            {{-- Other Courses --}}
                            @foreach(($otherCourses ?? collect()) as $course)
                                @continue(optional($shippingCourse)->id === $course->id)
                                <div class="col-md-3 mb-4">
                                    <div class="card h-375">
                                        @if($course->image)
                                            <img src="{{ asset('storage/'.$course->image) }}"
                                                 class="card-img-top card-img-80-center img-responsive"
                                                 alt="{{ $course->title }}">
                                        @endif

                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title text-center">{{ $course->title }}</h5>

                                            {{-- Description toggle button --}}
                                            <button class="btn btn-outline-secondary btn-sm desc-toggle"
                                                    type="button">
                                                Description
                                            </button>

                                            {{-- Description text (hidden by default, 75 chars max) --}}
                                            <div class="desc-text mt-2">
                                                <p class="mb-0">
                                                    {{
                                                        \Illuminate\Support\Str::limit(
                                                            $course->short_description
                                                                ?: $course->long_description
                                                                ?: 'No description yet.',
                                                            75
                                                        )
                                                    }}
                                                </p>
                                            </div>

                                            {{-- View button (will hide when description is open) --}}
                                            <a href="{{ route('home.courses.show', $course) }}"
                                               class="btn btn-primary btn-sm mt-3 view-btn">
                                                View Course
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach


                            {{-- Standalone Lessons --}}
                            @foreach(($standaloneLessons ?? collect()) as $lesson)
                                <div class="col-md-3 mb-4">
                                    <div class="card h-375">
                                        @if($lesson->image)
                                            <img src="{{ asset('storage/'.$lesson->image) }}"
                                                 class="card-img-top card-img-80-center img-responsive"
                                                 alt="{{ $lesson->title }}">
                                        @endif

                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title text-center">{{ $lesson->title }}</h5>

                                            {{-- Description toggle button --}}
                                            <button class="btn btn-outline-secondary btn-sm desc-toggle"
                                                    type="button">
                                                Description
                                            </button>

                                            {{-- Description text (hidden by default, 75 chars max) --}}
                                            <div class="desc-text mt-2">
                                                <p class="mb-0">
                                                    {{
                                                        \Illuminate\Support\Str::limit(
                                                            $lesson->short_description
                                                                ?: $lesson->long_description
                                                                ?: 'No description yet.',
                                                            75
                                                        )
                                                    }}
                                                </p>
                                            </div>

                                            {{-- View button (will hide when description is open) --}}
                                            <a href="{{ route('home.course_lesson', $lesson->id) }}"
                                               class="btn btn-primary btn-sm mt-3 view-btn">
                                                View Course
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            @endif




        @endsection

        @section('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    document.querySelectorAll('.desc-toggle').forEach(function (btn) {
                        btn.addEventListener('click', function () {
                            const card   = btn.closest('.card');
                            if (!card) return;

                            const img     = card.querySelector('.card-img-top');
                            const desc    = card.querySelector('.desc-text');
                            const viewBtn = card.querySelector('.view-btn');

                            const isOpen = card.classList.toggle('desc-open');

                            if (isOpen) {
                                // Shrink image
                                if (img) img.classList.add('card-img-collapsed');

                                // Show description
                                if (desc) desc.style.display = 'block';

                                // Hide view button
                                if (viewBtn) viewBtn.classList.add('d-none');

                                // Update button style/text
                                btn.classList.remove('btn-outline-secondary');
                                btn.classList.add('btn-secondary', 'active');
                                btn.textContent = 'Hide Description';
                            } else {
                                // Restore image
                                if (img) img.classList.remove('card-img-collapsed');

                                // Hide description
                                if (desc) desc.style.display = 'none';

                                // Show view button again
                                if (viewBtn) viewBtn.classList.remove('d-none');

                                // Reset button style/text
                                btn.classList.add('btn-outline-secondary');
                                btn.classList.remove('btn-secondary', 'active');
                                btn.textContent = 'Description';
                            }
                        });
                    });
                });
            </script>
        @endsection

</x-home-fullscreen-index>
