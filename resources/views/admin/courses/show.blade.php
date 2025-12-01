<x-admin-master>
    @section('page-title') {{ $course->title }} | Series @endsection

    @section('content')
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h1 class="m-0">{{ $course->title }}</h1>
                        <a href="{{ route('admin.courses.edit',$course) }}" class="btn btn-primary">
                            <i class="fa fa-edit"></i> Update Series
                        </a>
                    </div>
                </div>
            </div>

        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    @if($course->image ?? '')
                        <div class="col-sm-6">
                            <img src="{{ asset('storage/'.$course->image) }}" alt="{{ $course->title }} {{$course->image_title}}" class="img-fluid course-image">
                        </div>
                    @endif
                    @if($course->image)
                        <div class="col-sm-6">
                            @else
                        <div class="col-sm-12">
                    @endif
                        <p class="mb-1"><strong>Organization:</strong> {{ $course->organization?->name ?? '—' }}</p>
                        <p class="mb-1"><strong>Status:</strong> {{ $course->is_active ? 'Active' : 'Inactive' }}</p>
                        @if($course->short_description)<p class="mb-1">{{ $course->short_description }}</p>@endif
                        @if($course->long_description)<div class="preserve-lines">{{ $course->long_description }}</div>@endif
                    </div>
                </div>
            </div>
        </div>

            <!-- Courses Table -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6 col-sm-7">
                            <h3 class="m-0">Lessons ({{ $course->lessons->count() }})</h3>
                        </div>
                        <div class="col-6 col-sm-5">
                            <a href="{{ route('lesson.create', ['course' => $course->id]) }}" class="btn btn-outline-primary btn-right">
                                <i class="fa fa-plus"></i> Add Lesson
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTableLessons" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th class="text-center">Videos</th>
                                    <th class="text-center" style="width:120px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>

                            @forelse($course->lessons as $l)
                                <tr>
                                    <td>{{ $l->title }}</td>
                                    <td class="text-center">{{ collect($l->allVideoIds())->unique()->count() }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('lesson.view', $l->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted">No lessons yet.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /Courses Table -->

    @endsection
</x-admin-master>
