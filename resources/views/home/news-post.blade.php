<x-home-master>

    @section('page-title')
        {{$news->title}} | {{$settings->app_name}}
    @endsection

    @section('description')
        Merchants Exchange Learning Management System News
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
                <h1 class="my-4">{{$news->title}}</h1>
            </div>
            <!-- /Title -->

            <!-- Author -->
            <p class="lead">
                    by
                    <a href="#">{{$news->user->name}}</a>
            </p>

            <hr>

            <!-- Date/Time -->
            <p>Posted {{$news->created_at->diffForHumans()}}</p>

            <hr>

            <!-- Preview Image -->
            <img class="img-fluid rounded" src="{{$news->image}}" alt="{{$news->title}}">

            <hr>

            <!-- Post Content -->
            <p class="lead">{{$news->body}}</p>

            <blockquote class="blockquote">
                    <p class="mb-0">Ended lesson at "Creating a post from admin - part 1"</p>
                    <footer class="blockquote-footer">Someone famous in
                            <cite title="Source Title">Source Title</cite>
                    </footer>
            </blockquote>
            <hr>

            <!-- Comments Form -->
            <div class="card my-4">
                    <h5 class="card-header">Leave a Comment:</h5>
                    <div class="card-body">
                            <form>
                                    <div class="form-group">
                                            <textarea class="form-control" rows="3"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                    </div>
            </div>

            <!-- Single Comment -->
            <div class="media mb-4">
                    <img class="d-flex mr-3 rounded-circle" src="http://placehold.it/50x50" alt="">
                    <div class="media-body">
                            <h5 class="mt-0">Commenter Name</h5>
                            Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.
                    </div>
            </div>

            <!-- Comment with nested comments -->
            <div class="media mb-4">
                    <img class="d-flex mr-3 rounded-circle" src="http://placehold.it/50x50" alt="">
                    <div class="media-body">
                            <h5 class="mt-0">Commenter Name</h5>
                            Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.

                            <div class="media mt-4">
                                    <img class="d-flex mr-3 rounded-circle" src="http://placehold.it/50x50" alt="">
                                    <div class="media-body">
                                            <h5 class="mt-0">Commenter Name</h5>
                                            Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.
                                    </div>
                            </div>

                            <div class="media mt-4">
                                    <img class="d-flex mr-3 rounded-circle" src="http://placehold.it/50x50" alt="">
                                    <div class="media-body">
                                            <h5 class="mt-0">Commenter Name</h5>
                                            Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.
                                    </div>
                            </div>

                    </div>
            </div>


            @endsection

</x-home-master>
