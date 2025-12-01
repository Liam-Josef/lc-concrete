<x-home-fullscreen-master>

    @section('page-title')
        Lesson: {{$lesson->title}} | {{$settings->app_name}}
    @endsection

    @section('Description')
       {{$lesson->title}} | Merchants Exchange Learning Management System
    @endsection

    @section('background-image')
        {{ asset('storage/' . (($settings->internal_background ?? null) ?: 'app-images/interior-banner-1.jpg')) }}
    @endsection

    @section('styles')

    @endsection

    @section('content')
        <div class="wgite-back">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="my-4">{{$lesson->title}}</h1>
                </div>
                <div class="col-sm-6">
                    {{--                <a href="{{route('lesson.edit', $lesson->id)}}" class="btn btn-primary btn-right">Update Lesson</a>--}}
                </div>
            </div>
        </div>


            <div class="white-back">
                <div class="card shadow mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div>
                            <strong>{{ $lesson->title }}</strong>
                        </div>
                        <div class="small text-muted">
                            {{ $lesson->completed_videos_count ?? 0 }}/{{ $lesson->videos_count ?? 0 }} videos completed
                        </div>
                    </div>

                    <div class="card-body">
                        @php
                            $total = (int)($lesson->videos_count ?? 0);
                            $done  = (int)($lesson->completed_videos_count ?? 0);
                            $pct   = $total > 0 ? intval(($done / $total) * 100) : 0;
                        @endphp
                        <div class="progress mb-3" style="height: 6px;">
                            <div class="progress-bar" role="progressbar" style="width: {{ $pct }}%;" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>

                        {{ $lesson->short_description }}
                    </div>
                </div>
            </div>



        @endsection

    @section('scripts')








    @endsection

</x-home-fullscreen-index>
