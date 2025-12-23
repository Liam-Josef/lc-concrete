<!DOCTYPE html>
<html lang="en">

<head>

    @php($app = \App\Models\AppSetting::first())

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="@yield('description')">
    <meta name="author" content="Merchants Exchange">
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . (($settings->favicon ?? null) ?: '/storage/app-images/favicon.png')) }}">

    <title>@yield('page-title')</title>


    @if($app?->ga_measurement_id)
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $app->ga_measurement_id }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ $app->ga_measurement_id }}');
        </script>
    @endif

    @if($app?->gtm_container_id)
        <!-- Optional GTM head snippet if you use GTM -->
        <script>
            (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});
                var f=d.getElementsByTagName(s)[0], j=d.createElement(s), dl=l!='dataLayer'?'&l='+l:'';
                j.async=true; j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl; f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','{{ $app->gtm_container_id }}');
        </script>
    @endif

    @foreach(($app?->analytics_scripts ?? []) as $snippet)
        {!! $snippet !!}
    @endforeach

    <!-- Bootstrap core CSS -->
    <link href="{{asset('vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="{{asset('css/app.css')}}">

    <!-- Custom styles for this template -->
    <link href="{{asset('css/home.css')}}" rel="stylesheet">
    <link href="{{asset('css/admin.css')}}" rel="stylesheet">


    <style type="text/css">
        .home-back-img {
            background-image: url('{{ trim(View::yieldContent('background-image')) }}');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: left;
            background-attachment: fixed;
        }
    </style>

    @yield('style')
</head>

<body class="home-back-img d-flex flex-column min-vh-100">

<!-- Navigation -->
<x-home.header></x-home.header>

<!-- Page Content -->
<main>
    <div class="container-fluid">
        <div class="row">
            {{--        <div class="banner-container-home">--}}
            {{--            @yield('banner')--}}
            {{--        </div>--}}
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @yield('content')
            </div>
        </div>
        <!-- /.row -->
    </div>
</main>

<!-- Footer -->
<x-home.footer></x-home.footer>
<!-- /.container -->


<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-primary" type="submit">Logout</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Bootstrap core JavaScript -->
{{--<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>--}}
{{--<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>--}}
{{--<script src="{{asset('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>--}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

@yield('scripts')

<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://www.google.com/recaptcha/api.js?render={{ config('recaptcha.site_key') }}"></script>

<script>
    (function () {
        const SITE_KEY = '{{ config('recaptcha.site_key') }}';

        function getCsrf() {
            return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        }

        function bindRecaptchaToForm(form) {
            if (!form || form.dataset.recaptchaBound === '1') return;
            form.dataset.recaptchaBound = '1';

            form.addEventListener('submit', function (e) {
                // Only intercept if this form opts in
                if (form.dataset.recaptcha !== 'v3') return;

                e.preventDefault();

                if (typeof grecaptcha === 'undefined' || !SITE_KEY) {
                    console.error('reCAPTCHA not loaded or missing site key');
                    alert('Captcha failed to load. Please refresh and try again.');
                    return;
                }

                const action = form.dataset.recaptchaAction || 'submit';
                const isAjax = form.dataset.ajax === 'true';

                grecaptcha.ready(function () {
                    grecaptcha.execute(SITE_KEY, { action }).then(function (token) {
                        // ensure single, fresh token
                        let input = form.querySelector('input[name="g-recaptcha-response"]');
                        if (!input) {
                            input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'g-recaptcha-response';
                            form.appendChild(input);
                        }
                        input.value = token;

                        if (!isAjax) {
                            // Normal form post
                            form.submit();
                            return;
                        }

                        // AJAX submit (for modal/header login, etc.)
                        const fd = new FormData(form);
                        const submitBtn = form.querySelector('[type="submit"]');
                        submitBtn && submitBtn.setAttribute('disabled', 'disabled');

                        fetch(form.action, {
                            method: form.method || 'POST',
                            headers: { 'X-CSRF-TOKEN': getCsrf(), 'Accept': 'application/json' },
                            body: fd,
                            credentials: 'same-origin'
                        })
                            .then(async (res) => {
                                const data = await res.json().catch(() => ({}));
                                submitBtn && submitBtn.removeAttribute('disabled');

                                if (!res.ok) {
                                    showFormErrors(form, data.errors || [data.message || 'Request failed.']);
                                    return;
                                }

                                // Success: stay on this page (refresh) or follow redirect if provided
                                if (data.redirect) window.location.href = data.redirect;
                                else window.location.reload();
                            })
                            .catch(() => {
                                submitBtn && submitBtn.removeAttribute('disabled');
                                showFormErrors(form, ['Network error. Please try again.']);
                            });
                    }).catch(function () {
                        alert('Captcha failed to load. Please refresh and try again.');
                    });
                });
            });
        }

        function showFormErrors(form, errors) {
            const list = Array.isArray(errors) ? errors : Object.values(errors || {}).flat();
            let box = form.querySelector('.form-errors');
            if (!box) {
                box = document.createElement('div');
                box.className = 'form-errors alert alert-danger';
                form.prepend(box);
            }
            box.innerHTML = '<strong>Please fix the following:</strong><ul class="mb-0">' +
                list.map(e => `<li>${e}</li>`).join('') + '</ul>';
        }

        // Bind on DOM ready
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('form[data-recaptcha="v3"]').forEach(bindRecaptchaToForm);
        });

        // If forms get added dynamically (e.g., opening a modal), observe and bind
        const mo = new MutationObserver(() => {
            document.querySelectorAll('form[data-recaptcha="v3"]').forEach(bindRecaptchaToForm);
        });
        mo.observe(document.documentElement, { childList: true, subtree: true });
    })();
</script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const nav = document.getElementById('mainNav');
        const collapseEl = document.getElementById('navbarResponsive');
        if (!nav || !collapseEl) return;

        collapseEl.addEventListener('show.bs.collapse', () => nav.classList.add('nav-open'));
        collapseEl.addEventListener('hidden.bs.collapse', () => nav.classList.remove('nav-open'));
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const collapseEl = document.getElementById('navbarResponsive');
        if (!collapseEl || !window.bootstrap) return;

        collapseEl.querySelectorAll('a.nav-link').forEach(a => {
            a.addEventListener('click', () => {
                const inst = bootstrap.Collapse.getOrCreateInstance(collapseEl);
                inst.hide();
            });
        });
    });
</script>



</body>

</html>
