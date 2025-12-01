<x-home-fullscreen-index>

    @section('page-title')
        Registration Successful | {{$settings->app_name}}
    @endsection

    @section('description')
        Merchants Exchange Learning Management System
    @endsection

    @section('background-image')
        {{ asset('storage/' . (($settings->internal_background ?? null) ?: 'app-images/interior-banner-1.jpg')) }}
    @endsection

    @section('banner')
        <img src="{{asset('storage/app-images/interior-banner.jpg')}}" class="img-responsive" alt="MEX Learning Banner" title="MEX Learning Banner"/>
    @endsection

    @section('content')
            <!-- Title -->
            <div class="my-5">
                <h1 class="my-4">
                    You have successfully been registered
                    <small>Secondary Text</small>
                </h1>
            </div>
            <!-- /Title -->

        <h2>Proceed to your dashboard</h2>
    @endsection

</x-home-fullscreen-index>
