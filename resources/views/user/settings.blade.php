<x-home-fullscreen-index>

    @section('page-title')
        Settings | {{$settings->app_name}}
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

    @section('style')
        <style>
            footer {
                position: absolute;
                width: 100%;
                bottom: 0;
            }
        </style>
    @endsection

    @section('content')

     <div class="white-back"">
         <h1 class="my-4">Settings</h1>
     </div>



    @endsection

</x-home-fullscreen-index>

