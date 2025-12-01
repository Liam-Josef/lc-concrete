<x-home-fullscreen-master>

    @section('page-title')
        Student Dashboard | {{$settings->app_name}}
    @endsection

    @section('description')
        Merchants Exchange Learning Management System
    @endsection

    @section('background-image')
        {{ asset('storage/' . (($settings->internal_background ?? null) ?: 'app-images/interior-banner-1.jpg')) }}
    @endsection

    @section('banner')
        <img src="{{asset('storage/app-images/interior-banner.jpg')}}" class="img-responsive" alt="MEX Learning Banner" title="MEX Learning Banner"/>
    @endsection

    @section('style')
        <style>

        </style>
    @endsection

    @section('content')

       <div class="white-back mt-5">
           <h1 class="text-primary">Student Dashboard</h1>
       </div>

        <div class="white-back mt-3">
            <h3 class="text-primary">My Progress</h3>
            <div class="row text-center">
                <div class="col-sm-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Series</h5>
                            <p class="card-text">{{ $seriesCompleted }} / {{ $seriesTotal }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Lessons</h5>
                            <p class="card-text">{{ $lessonsCompletedAmongPaid }} / {{ $lessonsPaidTotal }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Videos</h5>
                            <p class="card-text">{{ $videosCompleted }} / {{ $videosTotal }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Hours Watched</h5>
                            <p class="card-text">{{ $hoursWatchedStr }} / {{ $hoursTotalStr }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="white-back mt-3">
            <h3 class="text-primary">Active Courses</h3>
           @if($lessons->isEmpty())
               <p>You are not registered for any lessons yet.</p>
           @else
               <div class="row">
                   @foreach($lessons as $lesson)
                       @php
                           $p = $progress[$lesson->id] ?? ['completed'=>0,'total'=>0,'percent'=>0];
                           $done = ($p['total'] > 0 && $p['completed'] >= $p['total']) || (bool)($lesson->pivot->complete ?? false);
                       @endphp
                       @if($done) @continue @endif
                       <div class="col-sm-4">
                           <div class="card mt-3">
                               <div class="card-header">
                                   <h3>{{$lesson->title}}</h3>
                               </div>
                               <div class="card-body">
                                   <img src="{{ asset('storage/' . $lesson->image) }}" class="img-responsive" alt="{{$lesson->title}}" title="{{$lesson->title}}"/>

                                   <!-- Progress Bar -->
                                   @php($p = $progress[$lesson->id] ?? ['completed'=>0,'total'=>0,'percent'=>0])

                                   <div class="mb-2">
                                       <div class="d-flex justify-content-between small mb-1">
                                           <span>{{ $p['completed'] }} / {{ $p['total'] }} videos</span>
                                           <span>{{ $p['percent'] }}%</span>
                                       </div>
                                       <div class="progress" style="height:8px;">
                                           <div
                                               class="progress-bar"
                                               role="progressbar"
                                               style="width: {{ $p['percent'] }}%;"
                                               aria-valuenow="{{ $p['percent'] }}"
                                               aria-valuemin="0"
                                               aria-valuemax="100">
                                           </div>
                                       </div>
                                   </div>
                                   <!-- /Progress Bar -->

                                   <a href="{{route("lessons.start", $lesson->id)}}" class="btn btn-primary btn-100 mt-3">Enter Course</a>
                               </div>
                           </div>
                       </div>
                   @endforeach
               </div>

                @auth
                    <div class="row mt-5">
                        <div class="col-12 col-sm-6 offset-sm-3 text-center">
                            <a
                                href="{{ route('user.profile', ['user' => auth()->id()]) }}"
                                class="btn btn-primary btn-100 btn-center"
                            >
                                View Your Courses
                            </a>
                        </div>
                    </div>
                @endauth

           @endif

       </div>


        @if($seriesSuggestions->isNotEmpty())
            <div class="white-back mt-3">
                <h3 class="text-primary">Complete a Series</h3>

                <div class="row">
                    @foreach($seriesSuggestions as $lesson)
                        <div class="col-sm-3 mb-4">
                            <div class="card h-100">
                                <div class="card-header">
                                    <div class="small text-muted">
                                        {{ $lesson->course->title ?? 'Series' }}
                                    </div>
                                    <h4 class="mb-0">{{ $lesson->title }}</h4>
                                </div>
                                <div class="card-body">
                                    @if($lesson->image)
                                        <img src="{{ asset('storage/'.$lesson->image) }}"
                                             class="img-fluid mb-3"
                                             alt="{{ $lesson->title }}" title="{{ $lesson->title }}">
                                    @endif

                                    <a href="{{ route('home.course_lesson', $lesson->id) }}"
                                       class="btn btn-primary btn-100 mt-2">View Course</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif



        @endsection

</x-home-fullscreen-master>
