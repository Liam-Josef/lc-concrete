<x-home-fullscreen-index>

    @section('page-title')
        {{$settings->app_name}}
    @endsection

    @section('description')
        Merchants Exchange Learning Management System
    @endsection

    @section('background-image')
            @php
                $bg = asset('storage/' . (($settings->home_background ?? null) ?: 'storage/app-images/home-banner-1.jpg'));
                $ver = @filemtime(public_path('storage/' . (($settings->home_background ?? null) ?: 'storage/app-images/home-banner-1.jpg')));
            @endphp

            {{ $bg }}?v={{ $ver }}
    @endsection

    @section('banner')
        <img src="{{asset('storage/app-images/home-banner-1.jpg')}}" class="img-responsive" alt="MEX Learning Banner" title="MEX Learning Banner"/>
    @endsection

    @section('content')

        <div class="home-slider mt-5 cement-shadow">
            <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
                <!-- Indicators -->
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true"></button>
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
                </div>

                <!-- Slides -->
                <div class="carousel-inner">
                    <div class="carousel-item active" data-bs-interval="5000">
                        <img src="{{asset('storage/app-images/banner-1.jpg')}}" class="d-block w-100 banner-img" alt="Banner 1">
                        <div class="carousel-caption">
                            <h2>Temporary Test Slidess</h2>
{{--                            <p>This may showcase a specific course / lesson</p>--}}
                        </div>
                    </div>

                    <div class="carousel-item" data-bs-interval="5000">
                        <img src="{{asset('storage/app-images/banner-2.jpg')}}" class="d-block w-100 banner-img" alt="Banner 2">
                        <div class="carousel-caption">
                            <h2>Temporary Test Slide 2</h2>
{{--                            <p>This may showcase a specific course / lesson</p>--}}
                        </div>
                    </div>

                    <div class="carousel-item" data-bs-interval="5000">
                        <img src="{{asset('storage/app-images/banner-3.jpg')}}" class="d-block w-100 banner-img" alt="Banner 3">
                        <div class="carousel-caption">
                            <h2>Temporary Test Slide 3</h2>
{{--                            <p>This may showcase a specific course / lesson</p>--}}
                        </div>
                    </div>
                </div>

                <!-- Controls -->
                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span><span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>

            <!-- Shipping Education Series -->
            @if($shippingCourse)
                <div class="white-back">
                    <div class="row">
                        <h2 class="text-primary">{{ $shippingCourse->title }}</h2>

                        @forelse($shippingCourse->lessons as $lesson)
                            <div class="col-sm-3 mb-4">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h3 class="h5 m-0">{{ $lesson->title }}</h3>
                                    </div>
                                    <div class="card-body">
                                        <a href="{{ route('home.course_lesson', $lesson->id) }}" class="text-decoration-none">
                                            @if(!empty($lesson->image))
                                                <img src="{{ Storage::url($lesson->image) }}" class="img-fluid mb-2" alt="{{ $lesson->title }}" title="{{ $lesson->title }}">
                                            @elseif(!empty($shippingCourse->image))
                                                {{-- fallback to course image if lesson has none --}}
                                                <img src="{{ Storage::url($shippingCourse->image) }}" class="img-fluid mb-2" alt="{{ $shippingCourse->title }}" title="{{ $shippingCourse->title }}">
                                            @endif
                                        </a>
                                        @if(!empty($lesson->short_description))
                                            <p class="mb-0">{{ \Illuminate\Support\Str::limit($lesson->short_description, 140) }}</p>
                                        @endif
                                        <a href="{{ route('home.course_lesson', $lesson->id) }}" class="btn btn-primary btn-100 mt-3">Learn More</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-muted">No lessons yet.</div>
                        @endforelse
                    </div>
                </div>
            @endif

            {{-- Additional Courses --}}
            @if(isset($otherCourses) && $otherCourses->count() > 0)
                <div class="white-back mt-4">
                    <div class="row">
                        <h2 class="text-primary">Courses</h2>

                        @foreach($otherCourses as $course)
                            @php
                                $short = trim($course->short_description ?? '');
                                $preview = \Illuminate\Support\Str::limit($short, 150, '');
                                $remainder = \Illuminate\Support\Str::substr($short, \Illuminate\Support\Str::length($preview));
                            @endphp

                            <div class="col-sm-3 mb-4">
                                <div class="card h-100">
                                    @if(!empty($course->image))
                                        <img src="{{ Storage::url($course->image) }}" class="card-img-top img-responsive" alt="{{ $course->title }}">
                                    @endif

                                    <div class="card-body d-flex flex-column">
                                        <h3 class="h5">{{ $course->title }}</h3>

                                        <p class="card-text">
                                            <span>{{ $preview }}</span>
                                            @if($remainder !== '')
                                                <span class="collapse" id="rest-{{ $course->id }}">{{ $remainder }}</span>
                                            @endif
                                        </p>

                                        @if($remainder !== '')
                                            <button
                                                class="btn btn-outline-secondary btn-sm desc-toggle mt-auto"
                                                type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#rest-{{ $course->id }}"
                                                aria-expanded="false"
                                                aria-controls="rest-{{ $course->id }}">
                                                Read more
                                            </button>
                                        @endif

                                        <a href="{{ route('home.courses.show', $course) }}" class="btn btn-primary btn-sm mt-3">
                                            View Course
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            @endif




{{--        <h1 class="my-4">Page Heading--}}
{{--            <small>Secondary Text</small>--}}
{{--        </h1>--}}

{{--        <!-- News Post -->--}}
{{--        @foreach($news as $post)--}}
{{--            <div class="card mb-4">--}}
{{--                <img class="card-img-top img-responsive" src="{{$post->image}}" alt="{{$post->title}}">--}}
{{--                <div class="card-body">--}}
{{--                    <h2 class="card-title">{{$post->title}}</h2>--}}
{{--                    <p class="card-text">{{Str::limit($post->body, '50', '...')}}</p>--}}
{{--                    <a href="{{route('home.news-post', $post->id)}}" class="btn btn-primary">Read More &rarr;</a>--}}
{{--                </div>--}}
{{--                <div class="card-footer text-muted">--}}
{{--                    Posted on {{$post->created_at->diffForHumans()}} by--}}
{{--                    <a href="#">Start Bootstrap</a>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        @endforeach--}}



{{--        <!-- Pagination -->--}}
{{--        <ul class="pagination justify-content-center mb-4">--}}
{{--            <li class="page-item">--}}
{{--                <a class="page-link" href="#">&larr; Older</a>--}}
{{--            </li>--}}
{{--            <li class="page-item disabled">--}}
{{--                <a class="page-link" href="#">Newer &rarr;</a>--}}
{{--            </li>--}}
{{--        </ul>--}}
    @endsection

</x-home-fullscreen-index>
