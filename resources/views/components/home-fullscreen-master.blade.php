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

    <!-- Bootstrap core CSS -->
    <link href="{{asset('vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="{{asset('css/app.css')}}">

    <!-- Custom styles for this template -->
    <link href="{{asset('css/home.css')}}" rel="stylesheet">
    <link href="{{asset('css/admin.css')}}" rel="stylesheet">


    <style type="text/css">
        .home-back-img {
            background-image: url(@yield('background-image'));
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
<!-- /.container -->

<!-- Footer -->
<x-home.footer></x-home.footer>

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

@yield('scripts')




</body>

</html>
