<x-admin-master>

    @section('page-title')
        Unsettled Transactions | MEX LMS Admin
    @endsection

    @section('styles')

    @endsection

    @section('content')
        <div class="row">
            <div class="col-sm-8">
                <h1 class="m-3">Unsettled Transactions</h1>
            </div>
            <div class="col-sm-4">
                <a href="{{route('lesson.create')}}" class="btn btn-primary btn-right mt-3 mr-2">
                    <i class="fa fa-plus"></i> All Transactions
                </a>
            </div>
        </div>


        <!-- Courses Table -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTableLessons" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>Course Title</th>
                            <th>Series</th>
                            <th class="text-center">Videos</th>
                            <th class="text-center">Students</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($lessons as $lesson)
                            <tr>
                                <td>{{ $lesson->title }}</td>
                                <td>
                                    @if($lesson->course)
                                        <a href="{{route('admin.courses.show', $lesson->course->id)}}">{{ $lesson->course->title }}</a>
                                    @endif
                                </td>
                                <td class="text-center">{{ $lesson->videos_count ?? 0 }}</td>
                                <td class="text-center">{{ $lesson->students_count ?? 0 }}</td>
                                <td>
                                    <div class="row">
                                        <div class="col-4">
                                            <a href="{{ route('lesson.view', $lesson->id) }}"><i class="fa fa-eye"></i></a>
                                        </div>
                                        <div class="col-4">
                                            <a href="{{ route('lesson.edit', $lesson->id) }}"><i class="fa fa-edit"></i></a>
                                        </div>
                                        <div class="col-4">
                                            <a href="#" data-toggle="modal" data-target="#deactivateLesson{{ $lesson->id }}"
                                               class="btn btn-link p-0 m-0 border-0 bg-transparent"><i class="fa fa-trash"></i></a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <!-- Deactivate Lesson Modal -->
                            <div class="modal fade" id="deactivateLesson{{$lesson->id}}" tabindex="-1" role="dialog" aria-labelledby="deactivateLessonModalModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-md" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h3>Deactivate "{{$lesson->title}}"</h3>
                                        </div>
                                        <div class="modal-body p-5">
                                            <form action="{{route('lesson.deactivate', $lesson->id)}}" method="post">
                                                @csrf
                                                @method('PUT')

                                                <button type="submit" class="btn btn-primary btn-100">Yes, DEACTIVATE</button>
                                            </form>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" data-dismiss="modal" class="btn btn-secondary mt-2 font-weight-bold btn-100">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /Deactivate Lesson Modal -->
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- /Courses Table -->


    @endsection

    @section('scripts')

    @endsection

</x-admin-master>
