<x-home-fullscreen-index>

    @section('page-title')
        Register | MEX Learning
    @endsection

    @section('description')
        Merchants Exchange Learning Management System
    @endsection

    @section('favicon')
        {{asset('/storage/app-images/favicon.png')}}
    @endsection

    @section('background-image')
        {{asset('storage/app-images/internal-banner-1.jpg')}}
    @endsection

    @section('banner')
        <img src="{{asset('storage/app-images/interior-banner.jpg')}}" class="img-responsive" alt="MEX Learning Banner" title="MEX Learning Banner"/>
    @endsection

    @section('content')

        <div class="container">
            <div class="card mb-5 mt-4">
                <div class="card-body">
                    <h1 class="mb-2 text-primary">Student Registration</h1>
                    <form method="POST" action="{{ route('student.register.store') }}" data-recaptcha="v3" data-recaptcha-action="register" id="registerForm">
                        @csrf

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>Please fix the following:</strong>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label">First Name</label>
                                <input
                                    type="text"
                                    name="first_name"
                                    value="{{ old('first_name') }}"
                                    class="form-control @error('first_name') is-invalid @enderror"
                                    required>
                                @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label">Last Name</label>
                                <input
                                    type="text"
                                    name="last_name"
                                    value="{{ old('last_name') }}"
                                    class="form-control @error('last_name') is-invalid @enderror"
                                    required>
                                @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror"
                                required>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input
                                type="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                required>
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input
                                type="password"
                                name="password_confirmation"
                                class="form-control"
                                required>
                        </div>

                        {{-- reCAPTCHA field errors (package uses this key) --}}
                        @error('g-recaptcha-response')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        {{-- if you previously showed @error('captcha'), you can keep both: --}}
                        @error('captcha')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror

                        <button type="submit" class="btn btn-primary">Register as Student</button>
                    </form>
                </div>
            </div>
        </div>

        @endsection

    @section('scripts')


    @endsection

</x-home-fullscreen-index>
