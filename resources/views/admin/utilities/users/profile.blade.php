<x-admin-master>

    @section('page-title')
    {{$user->name}} Profile | MEX LMS Admin
    @endsection

    @section('favicon')
        {{asset('/storage/app-images/favicon.png')}}
    @endsection

    @section('styles')

    @endsection

    @section('content')

        <div class="white-back">
            @if($lessons->isEmpty())
                <p>You are not registered for any lessons yet.</p>
                <a href="{{route('home.courses')}}" class="btn btn-primary my-4">Find a Course</a>
            @else
                <div class="row">
                    <!-- Lessons Table -->
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTableLessons" width="100%" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Series</th>
                                        <th>Progress</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($lessons as $lesson)
                                        <tr>
                                            <td>{{$lesson->title}}</td>
                                            <td>{{$lesson->organization}}</td>
                                            <td>
                                                <!-- Progress Bar -->
                                                @php($p = $progress[$lesson->id] ?? ['completed'=>0,'total'=>0,'percent'=>0])

                                                <div class="">
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
                                            </td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-4">
                                                        <a href="{{ route('student.course_lesson', $lesson->id) }}" title="View Lesson">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                    </div>

                                                    <div class="col-4">
                                                        @if(($eligibleMap[$lesson->id] ?? false) === true)
                                                            {{-- OPEN MODAL --}}
                                                            <a href="#" data-bs-toggle="modal" data-bs-target="#certModal{{$lesson->id}}" title="View Certificate">
                                                                <i class="fa fa-certificate text-primary"></i>
                                                            </a>

                                                            {{-- Modal with embedded certificate (iframe keeps it simple) --}}
                                                            <div class="modal fade" id="certModal{{$lesson->id}}" tabindex="-1" aria-labelledby="certModalLabel{{$lesson->id}}" aria-hidden="true">
                                                                <div class="modal-dialog modal-xl modal-dialog-centered">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 id="certModalLabel{{$lesson->id}}" class="modal-title">
                                                                                Certificate: {{ $lesson->title }}
                                                                            </h5>
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                        </div>
                                                                        <div class="modal-body p-0">
                                                                            <iframe
                                                                                src="{{ route('certificates.show', $lesson) }}"
                                                                                style="width:100%;height:70vh;border:0;"
                                                                            ></iframe>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            {{--                                                                            <a href="{{ route('certificates.download', $lesson) }}" class="btn btn-primary">--}}
                                                                            {{--                                                                                Download PDF--}}
                                                                            {{--                                                                            </a>--}}
                                                                            <button class="btn btn-outline-secondary" onclick="document.querySelector('#certModal{{$lesson->id}} iframe').contentWindow.print();">
                                                                                Print
                                                                            </button>
                                                                            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else
                                                            {{-- Disabled --}}
                                                            <a href="#" aria-disabled="true" tabindex="-1" class="no-click" title="Complete the lesson to unlock">
                                                                <i class="fa fa-certificate text-muted"></i>
                                                            </a>
                                                        @endif
                                                    </div>

                                                    <div class="col-4">
                                                        {{-- other actions (optional) --}}
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
                    <!-- /Lessons Table -->
                </div>
                @endif

        </div>

    @endsection

    @section('scripts')

    @endsection

</x-admin-master>
