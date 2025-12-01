<x-admin-master>

    @section('page-title')
        Inactive Series | MEX LMS Admin
    @endsection

    @section('styles')

    @endsection

    @section('content')
        <div class="row">
            <div class="col-sm-8">
                <h1 class="m-3">Inactive Series</h1>
            </div>
            <div class="col-sm-4">
                <a href="{{route('admin.courses.create')}}" class="btn btn-primary btn-right mt-3 mr-2 btn-100">
                    <i class="fa fa-plus"></i> Add a Series
                </a>
            </div>
        </div>


        <!-- Series Table -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTableLessons" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>Series</th>
                            <th>Organization</th>
                            <th class="text-center">Courses</th>
                            <th class="text-center">Videos</th>
                            <th class="text-center">Students</th>
                            <th class="text-end">Actions</th>
                        </tr>
                        </thead>
                        <tbody>

                        @forelse($courses as $course)
                            <tr>
                                <td>{{ $course->title }}</td>
                                <td>{{ $course->organization?->name ?? '—' }}</td>
                                <td class="text-center">{{ $course->lessons_count }}</td>
                                <td class="text-center">{{ $course->videos_count ?? '0' }}</td>
                                <td class="text-center">{{ $course->students_count ?? '0' }}</td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.courses.show',$course) }}">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.courses.edit',$course) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.courses.deactivate', $course) }}"
                                          method="POST"
                                          class="d-inline">
                                        @csrf
                                        @method('PUT')

                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Are you sure you want to deactivate this course series?')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>

                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted">No Series yet.</td></tr>
                        @endforelse

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- /Series Table -->


    @endsection

    @section('scripts')

    @endsection

</x-admin-master>
