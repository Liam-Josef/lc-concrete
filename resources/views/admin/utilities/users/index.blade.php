<x-admin-master>

    @section('page-title')
        Users | {{$settings->app_name}} Admin
    @endsection

    @section('favicon')
        {{asset('/storage/app-images/favicon.png')}}
    @endsection

    @section('styles')

    @endsection

    @section('content')
        <div class="row">
            <div class="col-sm-8">
                <h1 class="m-3">Users</h1>
            </div>
            <div class="col-sm-4">
                <a href="{{route('admin.utilities.user.create')}}" class="btn btn-primary btn-right mt-3 mr-2">
                    <i class="fa fa-plus"></i> Add User
                </a>
            </div>
        </div>


        <!-- Users Table -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="dataTableStudents" width="100%" cellspacing="0">
                            <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th class="text-center">Student</th>
                                <th style="width:220px;">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td class="fw-semibold">{{ $user->name }}</td>
                                    <td>
                                        <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                                    </td>

                                    <td class="text-center">
                                        @if($user->student)
                                            <span class="badge bg-success">Yes</span>
                                        @else
                                            <span class="badge bg-secondary">No</span>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="d-flex flex-wrap gap-2">
                                            {{-- View Profile --}}
                                            <a class="btn btn-outline-secondary btn-sm"
                                               href="{{ route('admin.utilities.user.profile', $user->id) }}" title="View Profile">
                                                <i class="fa fa-user me-1"></i>
                                            </a>

                                            {{-- Edit (use whatever you already have; these two exist in your navbar) --}}
                                            <a class="btn btn-primary btn-sm"
                                               href="{{ route('user.account', $user->id) }}" title="Edit User">
                                                <i class="fa fa-edit me-1"></i>
                                            </a>

                                            {{-- Register as Student (only show if admin and not already a student) --}}
                                            @if($user->is_admin && !$user->student)
                                                <form method="POST"
                                                      action="{{ route('admin.users.make-student', $user->id) }}"
                                                      onsubmit="return confirm('Create a Student profile for {{ $user->name }}?');">
                                                    @csrf
                                                    <button class="btn btn-success btn-sm" title="Register as Student">
                                                        <i class="fa fa-check me-1"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No users found.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- /Students Table -->


    @endsection

    @section('scripts')

    @endsection

</x-admin-master>
