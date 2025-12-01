<x-home-fullscreen-index>
    @section('page-title') {{ $course->title }} | {{$settings->app_name}} @endsection
    @section('description') Merchants Exchange Learning Management System @endsection
    @section('background-image')
        {{ asset('storage/' . (($settings->internal_background ?? null) ?: 'app-images/interior-banner-1.jpg')) }}
    @endsection
    @section('banner')
        <img src="{{asset('storage/app-images/interior-banner.jpg')}}" class="img-responsive" alt="MEX Learning Banner" title="MEX Learning Banner"/>
    @endsection

    @section('content')
            <!-- Title -->
            <div class="white-back mt-4">
                <div class="row">
                    <div class="col-sm-12">
                        <h1 class="text-primary mb-0">{{$course->title}}</h1>
                    </div>

                </div>
            </div>
            <!-- /Title -->

        <div class="white-back mt-3">
            <div class="row">
                @if($course->image)
                    <div class="col-sm-6">
                        <img src="{{ asset('storage/' . $course->image) }}" class="img-responsive" alt="{{ $course->title }}" title="{{ $course->title }}"/>
                    </div>
                    @else

                @endif
                <div class="
                 @if($course->image)
                col-sm-6
                @else
                col-sm-12 text-center
                @endif
                ">
                    <p>
                        {{$course->long_description ?? $course->short_description}}
                    </p>
                </div>
            </div>
        </div>



        <div class="white-back mt-3">
            <h2 class="text-primary">Series Courses</h2>
            @forelse($course->lessons as $lesson)
                <div class="col-sm-12">
                    <div class="card mt-3">
                        <div class="card-header">
                            <h3>{{ $lesson->title }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    @if($lesson->image)
                                        <img src="{{ asset('storage/' . $lesson->image) }}" class="img-responsive" alt="{{ $lesson->title }}" title="{{ $lesson->title }}"/>
                                    @endif
                                </div>
                                <div class="col-sm-6">
                                    <p>{{ $lesson->long_description ?: $lesson->short_description }}</p>
                                    <a href="{{ route('home.course_lesson', $lesson->id) }}" class="btn btn-primary btn-100 mt-5">Learn More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-sm-12 text-muted mt-4">No lessons yet for this course.</div>
            @endforelse
        </div>
    @endsection
</x-home-fullscreen-index>
