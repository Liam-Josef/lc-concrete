<nav class="navbar navbar-expand-lg navbar-dark bg-primary-transparent fixed-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home.index') }}">
          <span class="header-img">
            <img
                src="{{ asset('storage/' . (($settings->logo ?? null) ?: 'app-images/mex-learning-logo.png')) }}"
                alt="Merchants Exchange Learning System"
                class="img-responsive">
          </span>
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item {{ Request::is('/*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{route('home.index')}}">Main</a>
                </li>
                <li class="nav-item {{ Request::is('courses*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{route('home.portfolio')}}">Portfolio</a>
                </li>
                <li class="nav-item {{ Request::is('courses*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{route('home.contact')}}">Contact</a>
                </li>
                <li class="nav-item">
                    &nbsp;
{{--                    <a class="nav-link" href="{{route('home.news')}}">News</a>--}}
                </li>
{{--                @auth--}}
{{--                    @php--}}
{{--                        $user = Auth::user();--}}

{{--                        // Decide route based on admin or roles (supports single 'role' column or pivot 'roles')--}}
{{--                        $dashboardRoute = $user->is_admin--}}
{{--                            ? 'admin.index'--}}
{{--                            : ( $user->hasRole('student')--}}
{{--                                ? 'student.dashboard'--}}
{{--                                : ( $user->hasRole('instructor')--}}
{{--                                    ? 'instructor.dashboard'--}}
{{--                                    : null--}}
{{--                                )--}}
{{--                              );--}}
{{--                    @endphp--}}

{{--                    @if($user->is_admin && ($user->hasRole('student') ?? false))--}}
{{--                        <li class="nav-item">--}}
{{--                            <a class="nav-link {{ Request::routeIs('student.dashboard') ? 'active' : '' }}"--}}
{{--                               href="{{ route('student.dashboard') }}">--}}
{{--                                Dashboard--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                    @endif--}}

{{--                    @if ($dashboardRoute)--}}
{{--                        <li class="nav-item">--}}
{{--                            <a class="nav-link {{ Request::routeIs($dashboardRoute) ? 'active' : '' }}"--}}
{{--                               href="{{ route($dashboardRoute) }}">--}}
{{--                                {{ $user->is_admin ? 'Admin' : 'Dashboard' }}--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                    @endif--}}

{{--                    <li class="nav-item dropdown no-arrow">--}}
{{--                        <a class="nav-link dropdown-toggle"--}}
{{--                           href="#"--}}
{{--                           id="userDropdown"--}}
{{--                           role="button"--}}
{{--                           data-bs-toggle="dropdown"--}}
{{--                           aria-expanded="false">--}}
{{--                            {{ $user->name }}--}}
{{--                        </a>--}}

{{--                        <ul class="dropdown-menu dropdown-menu-end shadow"--}}
{{--                            aria-labelledby="userDropdown">--}}

{{--                            <li>--}}
{{--                                <a class="dropdown-item" href="{{ route('user.profile', $user->id) }}">--}}
{{--                                    <i class="fas fa-list fa-sm fa-fw me-2 text-gray-400"></i> My Courses--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <a class="dropdown-item" href="{{ route('user.account', $user->id) }}">--}}
{{--                                    <i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i> Account--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <a class="dropdown-item" href="{{ route('user.settings', $user->id) }}">--}}
{{--                                    <i class="fas fa-cogs fa-sm fa-fw me-2 text-gray-400"></i> Settings--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li><hr class="dropdown-divider"></li>--}}
{{--                            <li>--}}
{{--                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">--}}
{{--                                    <i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i> Logout--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                    </li>--}}
{{--                @else--}}
{{--                    <li class="nav-item dropdown {{ Request::is('login*') ? 'active' : '' }}">--}}
{{--                        <a class="nav-link dropdown-toggle"--}}
{{--                           href="#"--}}
{{--                           id="authDropdown"--}}
{{--                           role="button"--}}
{{--                           data-bs-toggle="dropdown"--}}
{{--                           aria-expanded="false">--}}
{{--                            Login--}}
{{--                        </a>--}}

{{--                        <div class="dropdown-menu dropdown-menu-end p-3"--}}
{{--                             aria-l1@abelledby="authDropdown"--}}
{{--                             style="min-width: 20rem;">--}}

{{--                            <form id="headerLoginForm"--}}
{{--                                  method="POST"--}}
{{--                                  action="{{ route('ajax.login') }}"--}}
{{--                                  data-recaptcha="v3"--}}
{{--                                  data-recaptcha-action="login"--}}
{{--                                  data-ajax="true"--}}
{{--                            >--}}
{{--                                @csrf--}}

{{--                                <div class="mb-2">--}}
{{--                                    <label for="navbarEmail" class="visually-hidden">Email</label>--}}
{{--                                    <input--}}
{{--                                        type="email"--}}
{{--                                        name="email"--}}
{{--                                        id="navbarEmail"--}}
{{--                                        class="form-control @error('email') is-invalid @enderror"--}}
{{--                                        placeholder="Email"--}}
{{--                                        value="{{ old('email') }}"--}}
{{--                                        required--}}
{{--                                        autocomplete="email"--}}
{{--                                    >--}}
{{--                                    @error('email')--}}
{{--                                    <div class="invalid-feedback d-block">{{ $message }}</div>--}}
{{--                                    @enderror--}}
{{--                                </div>--}}

{{--                                <div class="mb-2">--}}
{{--                                    <label for="navbarPassword" class="visually-hidden">Password</label>--}}
{{--                                    <input--}}
{{--                                        type="password"--}}
{{--                                        name="password"--}}
{{--                                        id="navbarPassword"--}}
{{--                                        class="form-control @error('password') is-invalid @enderror"--}}
{{--                                        placeholder="Password"--}}
{{--                                        required--}}
{{--                                        autocomplete="current-password"--}}
{{--                                    >--}}
{{--                                    @error('password')--}}
{{--                                    <div class="invalid-feedback d-block">{{ $message }}</div>--}}
{{--                                    @enderror--}}
{{--                                </div>--}}

{{--                                <div class="d-flex align-items-center justify-content-between mb-2">--}}
{{--                                    <div class="form-check">--}}
{{--                                        <input class="form-check-input"--}}
{{--                                               type="checkbox"--}}
{{--                                               name="remember"--}}
{{--                                               id="navbarRemember"--}}
{{--                                            {{ old('remember') ? 'checked' : '' }}>--}}
{{--                                        <label class="form-check-label" for="navbarRemember">Remember me</label>--}}
{{--                                    </div>--}}

{{--                                    @if (Route::has('password.request'))--}}
{{--                                        <a class="small" href="{{ route('password.request') }}">Forgot?</a>--}}
{{--                                    @endif--}}
{{--                                </div>--}}

{{--                                <button type="submit" class="btn btn-primary w-100">Sign in</button>--}}

{{--                                @if (Route::has('register'))--}}
{{--                                    <div class="text-center mt-2">--}}
{{--                                        <a class="small" href="{{ route('student.register') }}">Register</a>--}}
{{--                                    </div>--}}
{{--                                @endif--}}
{{--                            </form>--}}

{{--                        </div>--}}
{{--                    </li>--}}

{{--                @endauth--}}


            </ul>
        </div>
    </div>
</nav>
