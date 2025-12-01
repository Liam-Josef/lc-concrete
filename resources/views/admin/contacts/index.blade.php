<x-admin-master>

    @section('page-title')
        Contacts | MEX LMS Admin
    @endsection

    @section('favicon')
        {{asset('/storage/app-images/favicon.png')}}
    @endsection

    @section('styles')

    @endsection

    @section('content')
        <div class="row">
            <div class="col-sm-8">
                <h1 class="m-3">Contacts</h1>
            </div>
            <div class="col-sm-4">
                <a href="{{route('contact.create')}}" class="btn btn-primary btn-right mt-3 mr-2">
                    <i class="fa fa-plus"></i> Add Contacts
                </a>
            </div>
        </div>


        <!-- Contacts Table -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTableContacts" width="100%" cellspacing="0">
                        <thead>
                        <tr>
{{--                            <th></th>--}}
                            <th>Name</th>
                            <th>Email</th>
                            <th>Organization</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($contacts as $contact)
                            <tr>
{{--                                <td></td>--}}
                                <td>{{$contact->first_name}} {{$contact->last_name}}</td>
                                <td>
                                    <a href="mailto:{{$contact->email}}">{{$contact->email}}</a>
                                </td>
                                <td>
                                    @foreach($contact->organizations as $org)
                                        <span class="badge bg-secondary">{{ $org->name }}</span>
                                    @endforeach
                                </td>
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
                                            <a href="#" data-toggle="modal" data-target="#deactivateContact{{$contact->id}}" class="btn btn-link p-0 m-0 border-0 bg-transparent">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <!-- Deactivate Lesson Modal -->
                            <div class="modal fade" id="deactivateContact{{$contact->id}}" tabindex="-1" role="dialog" aria-labelledby="deactivateLessonModalModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-md" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h3>Deactivate "{{$contact->first_name}} {{$contact->last_name}}"</h3>
                                        </div>
                                        <div class="modal-body p-5">
                                            <form action="{{route('contact.deactivate', $contact->id)}}" method="post">
                                                @csrf
                                                @method('PUT')

                                                <button type="submit" class="btn btn-primary btn-100">Yes, Deactivate</button>
                                            </form>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" data-dismiss="modal" class="btn btn-secondary mt-2 font-weight-bold btn-100">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /Deactivate Lesson Modal -->
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
