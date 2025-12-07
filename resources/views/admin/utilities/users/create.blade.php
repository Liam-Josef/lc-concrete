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
                <div class="col-sm-12">
                    <div class="card shadow mb-4">
                        <div class="card-header">
                            <h5 class="m-0">Add Admin User</h5>
                        </div>
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $e)
                                            <li>{{ $e }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('admin.utilities.user.store') }}">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">First Name</label>
                                        <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Last Name</label>
                                        <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="col-sm-12">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="col-sm-12">
                                        <label class="form-label">
                                            Password
                                            <small class="text-muted">(leave blank to auto-generate)</small>
                                        </label>
                                        <input type="password" name="password" class="form-control" minlength="8" autocomplete="new-password">
                                    </div>
                                </div>

                                <div class="form-check mb-3">
                                    <div class="col-sm-12">
                                        <input class="form-check-input" type="checkbox" value="1" id="make_student" name="make_student" {{ old('make_student') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="make_student">
                                            Also create a Student profile for this user
                                        </label>
                                    </div>
                                </div>

                                <div class="col-sm-12">
                                    <button class="btn btn-primary">Create User</button>
                                    <a href="{{ route('admin.utilities.user.index') }}" class="btn btn-outline-secondary">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>

                    @if (session('generated_password'))
                        <div class="alert alert-warning">
                            <strong>Generated Password:</strong>
                            <code>{{ session('generated_password') }}</code>
                            <br>
                            Copy this now—it's shown only once.
                        </div>
                    @endif
                </div>
            </div>
        @endsection

    @section('scripts')

    @endsection

</x-admin-master>
