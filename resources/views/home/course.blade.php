{{--<x-home-fullscreen-index>--}}
{{--    @section('page-title') Courses | MEX Learning @endsection--}}
{{--    @section('description') Merchants Exchange Learning Management System @endsection--}}
{{--    @section('favicon') {{asset('/storage/app-images/favicon.png')}} @endsection--}}
{{--    @section('background-image') {{asset('storage/app-images/internal-banner-1.jpg')}} @endsection--}}
{{--    @section('banner')--}}
{{--        <img src="{{asset('storage/app-images/interior-banner.jpg')}}" class="img-responsive" alt="MEX Learning Banner" title="MEX Learning Banner"/>--}}
{{--    @endsection--}}

{{--    @section('content')--}}
{{--        <!-- Title -->--}}
{{--        <div class="page-title-image">--}}
{{--            <img src="{{asset('storage/app-images/interior-banner.jpg')}}" class="img-responsive" alt="MEX Learning Banner" title="MEX Learning Banner"/>--}}
{{--            <span class="overlay-text"><h1>Courses</h1></span>--}}
{{--        </div>--}}

{{--        <div class="white-back">--}}
{{--            --}}{{-- Shipping Education Series (top) --}}
{{--            @if($shippingCourse)--}}
{{--                <div class="col-sm-12">--}}
{{--                    <div class="card">--}}
{{--                        <div class="card-header">--}}
{{--                            <h3>{{ $shippingCourse->title }}</h3>--}}
{{--                        </div>--}}
{{--                        <div class="card-body">--}}
{{--                            <div class="row">--}}
{{--                                --}}{{-- Lessons grid (link each image to the lesson page) --}}
{{--                                <div class="col-sm-6">--}}
{{--                                    <div class="row">--}}
{{--                                        @forelse($shippingCourse->lessons as $lesson)--}}
{{--                                            <div class="col-6 mb-3">--}}
{{--                                                <a href="{{ route('home.course_lesson', $lesson->id) }}" class="text-decoration-none">--}}
{{--                                                    @if($lesson->image)--}}
{{--                                                        <img src="{{ asset('storage/'.$lesson->image) }}" class="img-responsive mb-2" alt="{{ $lesson->title }}" title="{{ $lesson->title }}"/>--}}
{{--                                                    @endif--}}
{{--                                                    <div class="small fw-bold text-dark">{{ $lesson->title }}</div>--}}
{{--                                                </a>--}}
{{--                                            </div>--}}
{{--                                        @empty--}}
{{--                                            <div class="col-12 text-muted">No lessons yet.</div>--}}
{{--                                        @endforelse--}}
{{--                                    </div>--}}
{{--                                </div>--}}

{{--                                --}}{{-- Series blurb + link to course page --}}
{{--                                <div class="col-sm-6">--}}
{{--                                    <p class="mb-4">--}}
{{--                                        This Shipping Education Series is committed to providing the most comprehensive and relevant maritime education available…--}}
{{--                                    </p>--}}

{{--                                    <a href="{{ route('home.courses.show', $shippingCourse) }}" class="btn btn-primary btn-100">--}}
{{--                                        Learn More--}}
{{--                                    </a>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            @endif--}}
{{--        </div>--}}

{{--        --}}{{-- Additional Courses (only if any exist) --}}
{{--        @if(isset($otherCourses) && $otherCourses->count() > 0)--}}
{{--            <div class="white-back mt-4">--}}
{{--                <div class="col-sm-12">--}}
{{--                    <h3 class="mb-3">Additional Courses</h3>--}}
{{--                    <div class="row">--}}
{{--                        @foreach($otherCourses as $course)--}}
{{--                            <div class="col-md-3 mb-4">--}}
{{--                                <div class="card h-100">--}}
{{--                                    @if($course->image)--}}
{{--                                        <img src="{{ asset('storage/'.$course->image) }}" class="card-img-top" alt="{{ $course->title }}">--}}
{{--                                    @endif--}}
{{--                                    <div class="card-body d-flex flex-column">--}}
{{--                                        <h5 class="card-title">{{ $course->title }}</h5>--}}

{{--                                        <button class="btn btn-outline-secondary btn-sm mt-auto"--}}
{{--                                                type="button"--}}
{{--                                                data-bs-toggle="collapse"--}}
{{--                                                data-bs-target="#desc-{{ $course->id }}"--}}
{{--                                                aria-expanded="false"--}}
{{--                                                aria-controls="desc-{{ $course->id }}">--}}
{{--                                            Description--}}
{{--                                        </button>--}}

{{--                                        <div class="collapse mt-2" id="desc-{{ $course->id }}">--}}
{{--                                            <p class="card-text mb-0">--}}
{{--                                                {{ $course->short_description ?: Str::limit($course->long_description, 160) ?: 'No description yet.' }}--}}
{{--                                            </p>--}}
{{--                                        </div>--}}

{{--                                        <a href="{{ route('home.courses.show', $course) }}" class="btn btn-primary btn-sm mt-3">--}}
{{--                                            View Course--}}
{{--                                        </a>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        @endforeach--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        @endif--}}
{{--    @endsection--}}
{{--</x-home-fullscreen-index>--}}
