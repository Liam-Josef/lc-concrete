<x-admin-master>

    @section('page-title')
        Org: {{$organization->name}} | MEX LMS Admin
    @endsection

    @section('favicon')
        {{asset('/storage/app-images/favicon.png')}}
    @endsection

    @section('styles')

    @endsection

    @section('content')
        <div class="row">
            <div class="col-sm-12">
                <h1 class="m-3">{{$organization->name}}</h1>
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

        <!-- Nav Tabs -->
        <ul class="nav nav-tabs profile-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="active-tab" data-bs-toggle="tab" href="#active" role="tab" aria-controls="active" aria-selected="true">Organization Info</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="lessons-tab" data-bs-toggle="tab" href="#lessons" role="tab" aria-controls="lessons" aria-selected="false">Lessons</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Contacts</a>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content mt-3" id="myTabContent">
            <div class="tab-pane fade show active" id="active" role="tabpanel" aria-labelledby="active-tab">
                <!-- Organization Info -->
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4>Organization Info</h4>
                            </div>
                            <div class="col-sm-4">
                                <a href="{{route('organization.edit', $organization->id)}}" class="btn btn-primary btn-right">Update Org</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <h5>Email</h5>
                                <p>
                                    <a href="mailto:{{$organization->email}}">{{$organization->email}}</a>
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
                            <div class="col-sm-4">
                                <h5>Website</h5>
                                <p>
                                    <a href="{{$organization->website}}">{{$organization->website}}</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Organization Info -->
            </div>
            <div class="tab-pane fade" id="lessons" role="tabpanel" aria-labelledby="lessons-tab">
                <!-- Organization Lessons -->
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4>Lessons ( {{ $organization->lessons?->count() ?? 0 }} )</h4>
                            </div>
                            <div class="col-sm-4">
                                <a href="#" data-toggle="modal" data-target="#addLesson" class="btn btn-primary btn-right">Add Lesson</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <hr>
                        @if ($organization->lessons?->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTableLessons" width="100%" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th>Title</th>
                                        <th>Videos</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($organization->lessons as $lesson)
                                        <tr>
                                            <td></td>
                                            <td>{{$lesson->title}}</td>
                                            {{--                                <td>61</td>--}}
                                            <td>{{$lesson->organization}}</td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-4">
                                                        <a href="{{route('lesson.view', $lesson->id)}}">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                    </div>
                                                    <div class="col-4">
                                                        <a href="{{route('lesson.edit', $lesson->id)}}">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                    </div>
                                                    <div class="col-4">
                                                        <a href="#" data-toggle="modal" data-target="#deactivateLesson{{$lesson->id}}"
                                                           class="btn btn-link p-0 m-0 border-0 bg-transparent"><i class="fa fa-trash"></i></a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Deactivate Lesson Modal -->
                                        <div class="modal fade" id="deactivateLesson{{$lesson->id}}" tabindex="-1" role="dialog" aria-labelledby="deactivateLessonModalModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-md" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h3>Deactivate "{{$lesson->title}}"</h3>
                                                    </div>
                                                    <div class="modal-body p-5">
                                                        <form action="{{route('lesson.deactivate', $lesson->id)}}" method="post">
                                                            @csrf
                                                            @method('PUT')

                                                            <button type="submit" class="btn btn-primary btn-100">Yes, DEACTIVATE</button>
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

                        @else
                            No Lessons Yet
                        @endif


                    </div>
                </div>
                <!-- /Organization Lessons -->
            </div>
            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                <!-- Organization Contacts -->
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-8">
                                <h4>Contacts</h4>
                            </div>
                            <div class="col-sm-4">
                                <a href="#" data-toggle="modal" data-target="#addContact" class="btn btn-primary btn-right">Add Contact</a>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        @if (!empty($organization->contacts) && $organization->contacts->count() > 0)

                            <!-- Organization Contacts Table -->
                            <div class="card shadow mb-4">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="dataTableContacts" width="100%" cellspacing="0">
                                            <thead>
                                            <tr>
                                                {{--                            <th></th>--}}
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($organization->contacts as $contact)
                                                <tr>
                                                    {{--                                <td></td>--}}
                                                    <td>{{$contact->first_name}} {{$contact->last_name}}</td>
                                                    <td>
                                                        <a href="mailto:{{$contact->email}}">{{$contact->email}}</a>
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
                            <!-- /Organization Contacts Table -->
                        @else
                            <p>No contacts linked to this organization.</p>
                        @endif
                    </div>
                </div>
                <!-- /Organization Contacts -->
            </div>
        </div>


        <!-- Add Lesson Modal -->
        <div class="modal fade" id="addLesson" tabindex="-1" role="dialog" aria-labelledby="addLessonModalModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3>Add a {{$organization->name}} Lesson</h3>
                    </div>
                    <form action="{{ route('lesson.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('POST')

                        <div class="modal-body p-5">

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="is_active">Active</label>
                                        <select name="is_active" id="is_active">
                                            <option value="1" selected>Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label for="title" class="mt-3">Lesson Title</label>
                                        <input type="text" class="form-control" name="title">
                                    </div>
                                    <div class="col-sm-12">
                                        <label for="short_description" class="mt-3">Short Description</label>
                                        <input type="text" class="form-control" name="short_description">
                                    </div>
                                    <div class="col-sm-12">
                                        <label for="long_description" class="mt-3">Long Description</label>
                                        <textarea name="long_description" id="long_description" cols="30" rows="10" class="form-control-plaintext"></textarea>
                                    </div>
                                    <div class="col-sm-12">
                                        <label for="event_link" class="mt-3">Event Link</label>
                                        <input type="text" class="form-control" name="event_link">
                                    </div>
                                    <div class="col-6">
                                        <label for="total_hours" class="mt-3">Total Hours</label>
                                        <input type="text" class="form-control" name="total_hours">
                                    </div>
                                    <div class="col-6">
                                        <label for="total_ceu" class="mt-3">Total CEU</label>
                                        <input type="text" class="form-control" name="total_ceu">
                                    </div>
                                    <div class="col-4">
                                        <label for="student_cost" class="mt-3">Student Cost</label>
                                        <input type="text" class="form-control" name="student_cost">
                                    </div>
                                    <div class="col-4">
                                        <label for="platform_cost" class="mt-3">Platform Cost</label>
                                        <input type="text" class="form-control" name="platform_cost">
                                    </div>
                                    <div class="col-4">
                                        <label for="pay_to_organization" class="mt-3">Org Net Profit</label>
                                        <input type="text" class="form-control" name="pay_to_organization">
                                    </div>
                                    <div class="col-12 mt-3">
                                        <label for="image">Upload Image</label>
                                        <input type="file" class="form-control" name="image" accept="image/*">
                                    </div>

                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <div class="row width-100">
                                <div class="col-sm-6">
                                    <button type="button" data-dismiss="modal" class="btn btn-secondary font-weight-bold btn-100">Cancel</button>
                                </div>
                                <div class="col-sm-6">
                                    <input type="hidden" name="organization_id" value="{{$organization->id}}">
                                    <input type="hidden" name="redirect_url" value="{{ url()->current() }}">
                                    <button type="submit" class="btn btn-primary btn-100">Add Lesson</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /Add Lesson Modal -->

        <!-- Add Contact  Modal -->
        <div class="modal fade" id="addContact" tabindex="-1" role="dialog" aria-labelledby="addContactModalModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3>Add a {{$organization->name}} Contact</h3>
                    </div>
                    <form action="{{ route('contact.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('POST')

                        <div class="modal-body p-5">

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="is_active">Active</label>
                                        <select name="is_active" id="is_active">
                                            <option value="1" selected>Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label for="is_lead">Is Lead</label>
                                        <select name="is_lead" id="is_lead">
                                            <option value="1">Yes</option>
                                            <option value="0" selected>No</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label for="first_name" class="mt-3">First Name</label>
                                        <input type="text" class="form-control" name="first_name">
                                    </div>
                                    <div class="col-6">
                                        <label for="last_name" class="mt-3">Last Name</label>
                                        <input type="text" class="form-control" name="last_name">
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="phone" class="mt-3">Phone Number</label>
                                        <input type="text" class="form-control" name="phone">
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="email" class="mt-3">Email</label>
                                        <input type="email" class="form-control" name="email">
                                    </div>

                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <div class="row width-100">
                                <div class="col-sm-6">
                                    <button type="button" data-dismiss="modal" class="btn btn-secondary font-weight-bold btn-100">Cancel</button>
                                </div>
                                <div class="col-sm-6">
                                    <input type="hidden" name="organization_id" value="{{$organization->id}}">
                                    <input type="hidden" name="redirect_url" value="{{ url()->current() }}">
                                    <button type="submit" class="btn btn-primary btn-100">Add Contact</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /Add Contact  Modal -->


    @endsection

    @section('scripts')

    @endsection

</x-admin-master>
