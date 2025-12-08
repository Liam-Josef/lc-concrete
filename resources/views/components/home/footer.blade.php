<footer class="pt-2 index mt-auto bg-lite-transparent bg-opacity-50 w-100">
    <div class="container">
        <div class="footer-img">
            <img src="{{ asset('storage/' . (($settings->logo ?? null) ?: 'app-images/mex-learning-logo.png')) }}">
        </div>
        <p class="m-0 text-center text-white mb-3">
            <a href="https://www.facebook.com/lakaiconcrete/" target="_blank" class="mr-3"><i class="fab fa-facebook fa-2x"></i>
            <a href="https://www.instagram.com/lakaiconcrete/" target="_blankd" class="mr-3"><i class="fab fa-instagram fa-2x"></i>
            <a href="https://share.google/wMHiDQUcqVvCgwARo" target="_blank"><i class="fab fa-google-plus fa-2x"></i></a>
        </p>
{{--        <p class="m-0 text-center text-white">--}}
{{--            <a href="#">About</a> &nbsp; | &nbsp;--}}
{{--            <a href="#">Portfolio</a> &nbsp; | &nbsp;--}}
{{--            <a href="#">Contact</a>--}}
{{--            --}}{{--            <a href="#">Contact</a> &nbsp; | &nbsp;--}}
{{--            --}}{{--            <a href="#">Terms</a> &nbsp; | &nbsp;--}}
{{--            --}}{{--            <a href="#">Privacy</a>--}}
{{--        </p>--}}
    </div>
    <!-- /.container -->
    <div class="footer-bottom p-1">
        <p class="m-0 text-center text-white pb-2">
            Copyright &copy; {{$settings->app_name}} {{ date('Y') }}
            <span style="display: none">Built by <a href="https://liamjosef.com/" target="_blank" class="text-white">Liam Josef</a></span>
        </p>

    </div>
</footer>
