<x-admin-master>

    @section('page-title')
        Inactive Contacts | MEX LMS Admin
    @endsection

    @section('favicon')
        {{asset('/storage/app-images/favicon.png')}}
    @endsection

    @section('styles')

    @endsection

    @section('content')
        <h1 class="m-3">Inactive Contacts</h1>


        <!-- Contacts Table -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTableContacts" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Email</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($contacts as $contact)
                            <tr>
                                <td></td>
                                <td>{{$contact->first_name}} {{$contact->last_name}}</td>
                                <td>{{$contact->email}}</td>
                                <td>
                                    <div class="row">
                                        <div class="col-4">
                                            <a href="{{route('contact.view', $contact->id)}}">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </div>
                                        <div class="col-4">
                                            <a href="{{route('contact.edit', $contact->id)}}">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        </div>
                                        <div class="col-4">
                                            <form action="{{route('contact.activate', $contact->id)}}" method="post">
                                                @csrf
                                                @method('PUT')

                                                <button type="submit" class="btn btn-link p-0 m-0 border-0 bg-transparent"><i class="fa fa-check"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- /Contacts Table -->


    @endsection

    @section('scripts')

    @endsection

</x-admin-master>
