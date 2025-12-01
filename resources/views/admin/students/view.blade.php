<x-admin-master>

    @section('page-title')
        Student: {{$student->first_name}} {{$student->last_name}} | MEX LMS Admin
    @endsection

    @section('favicon')
        {{asset('/storage/app-images/favicon.png')}}
    @endsection

    @section('styles')

    @endsection

    @section('content')
        <div class="row">
            <div class="col-sm-6">
                <h1 class="m-3">{{$student->first_name}} {{$student->last_name}}</h1>
            </div>
            <div class="col-sm-6">
                <a href="{{route('student.edit', $student->id)}}" class="btn btn-primary btn-right">Update Student</a>
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
        </div>

        <!-- Student Info -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-4">
                        <h5>Email</h5>
                        <p>
                            <a href="mailto:{{$student->email}}">{{$student->email}}</a>
                        </p>
                    </div>
                    <div class="col-sm-4">
                        <h5>Phone</h5>
                        <p>
                            <a href="tel:{{$student->phone}}">{{$student->phone}}</a>
                        </p>
                    </div>
                    <div class="col-sm-4">
                        <h5 class="text-right">Lessons</h5>
                        <p class="text-right">
                            {{ $student->lessons->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Student Info -->

        <!-- Student Lessons Table -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTableStudentLessons" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Title</th>
                            {{--                            <th>Instructor</th>--}}
                            <th>Organization</th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($student->lessons as $lesson)
                            @php
                                $totalVideos = $lesson->videos->count();
                                $completedVideos = $lesson->videos->filter(function($video) use ($student) {
                                    return $student->videos->contains($video->id);
                                })->count();
                                $isComplete = $totalVideos > 0 && $totalVideos === $completedVideos;
                            @endphp

                            <tr>
                                <td></td>
                                <td>{{$lesson->title}}</td>
                                {{--                                <td>61</td>--}}
                                <td>{{$lesson->organization}}</td>

                                <td>
                                    {{ $completedVideos }} / {{ $totalVideos }} Videos
                                </td>

                                <td>
                                    <div class="row">
                                        <div class="col-4">
                                            <a href="{{ route('lesson.view', $lesson->id) }}">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </div>
                                        <div class="col-4">
                                            @if($isComplete)
                                                <i class="fa fa-check-circle text-success" title="Completed"></i>
                                            @else
                                                <i class="fa fa-minus-circle text-danger" title="Incomplete"></i>
                                            @endif
                                        </div>
                                        <div class="col-4">

                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- /Student Lessons Table -->


    @endsection

    @section('scripts')

    @endsection

</x-admin-master>
