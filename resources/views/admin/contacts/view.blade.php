<x-admin-master>

    @section('page-title')
        Contact: {{$contact->first_name}} {{$contact->last_name}} | MEX LMS Admin
    @endsection

    @section('favicon')
        {{asset('/storage/app-images/favicon.png')}}
    @endsection

    @section('styles')

    @endsection

    @section('content')
        <div class="row">
            <div class="col-sm-6">
                <h1 class="m-3">{{$contact->first_name}} {{$contact->last_name}}</h1>
            </div>
            <div class="col-sm-6">
                <a href="{{route('contact.edit', $contact->id)}}" class="btn btn-primary btn-right">Update Contact</a>
            </div>
        </div>


        <div class="card shadow mb-4">
            @if ($errors->any())
                <div class="card-header">
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>

        <!-- Contact Info -->
        <div class="card shadow mb-4">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-6">
                            <h4>Contact Info</h4>
                        </div>
                        <div class="col-sm-6">
                            @if($contact->is_lead == 1)
                                <h5 class="text-primary text-right mb-0">LEAD</h5>
                                @else
                                &nbsp;
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <h5>Email</h5>
                            <p>
                                <a href="mailto:{{$contact->email}}">{{$contact->email}}</a>
                            </p>
                        </div>
                        <div class="col-sm-4">
                            <h5>Phone</h5>
                            <p>
                                <a href="tel:{{$contact->phone}}">{{$contact->phone}}</a>
                            </p>
                        </div>
                        <div class="col-sm-4">
                           &nbsp;
                        </div>
                    </div>
                </div>
            </div>
        <!-- /Contact Info -->

        <!-- Contact Organization Info -->
        @foreach($contact->organizations as $organization)
        <div class="card shadow mb-4">
                <div class="card-header">
                    <h4>Organization Info</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-12">
                            <h3>{{$organization->name}}</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <h5>Email</h5>
                            <p>
                                <a href="mailto:{{$organization->email}}">{{$organization->email}}</a>
                            </p>

                            <br>

                            <h5>Website</h5>
                            <p>
                                <a href="{{$organization->website}}">{{$organization->website}}</a>
                            </p>
                        </div>
                        <div class="col-sm-4">
                            <h5>Phone</h5>
                            <p>
                                <a href="tel:{{$organization->phone}}">{{$organization->phone}}</a>
                            </p>
                        </div>
                        <div class="col-sm-4">
                            <h5>Address</h5>
                            <p>
                                {{$organization->address_1}} <br>
                                {{$organization->address_2}} <br>
                                {{$organization->city}}, {{$organization->state}} {{$organization->zip}}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <!-- /Contact Organization Info -->


    @endsection

    @section('scripts')

    @endsection

</x-admin-master>
