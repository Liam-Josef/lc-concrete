<x-home-fullscreen-index>

@section('page-title')
    Privacy Policy | {{$settings->app_name}}
@endsection

@section('description')
    Merchants Exchange Learning Management System Privacy Policy
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
        <h1 class="my-4">Privacy Policy</h1>
    </div>
    <!-- /Title -->

    <!-- News Post -->
    @foreach($news as $post)
        <div class="card mb-4">
            <img class="card-img-top" src="{{$post->image}}" alt="{{$post->title}}">
            <div class="card-body">
                <h2 class="card-title">{{$post->title}}</h2>
                <p class="card-text">{{Str::limit($post->body, '50', '...')}}</p>
                <a href="{{route('home.news-post', $post->id)}}" class="btn btn-primary">Read More &rarr;</a>
            </div>
            <div class="card-footer text-muted">
                Posted on {{$post->created_at->diffForHumans()}} by
                <a href="#">Start Bootstrap</a>
            </div>
        </div>
    @endforeach

    <!-- Pagination -->
    <ul class="pagination justify-content-center mb-4">
        <li class="page-item">
            <a class="page-link" href="#">&larr; Older</a>
        </li>
        <li class="page-item disabled">
            <a class="page-link" href="#">Newer &rarr;</a>
        </li>
    </ul>
    @endsection

    </x-home-fullscreen-index>
