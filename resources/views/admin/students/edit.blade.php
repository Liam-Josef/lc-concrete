<x-admin-master>

    @section('page-title')
        Update: {{$student->first_name}} {{$student->last_name}} | MEX LMS Admin
    @endsection

    @section('favicon')
        {{asset('/storage/app-images/favicon.png')}}
    @endsection

    @section('styles')

    @endsection

    @section('content')
        <h1 class="m-3"><span>Update:</span> {{$student->first_name}} {{$student->last_name}}</h1>


        <!-- Organizations Form -->
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
            <div class="card-body">
                <form action="{{ route('student.update', $student->id) }}" method="post">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <div class="row">
                            <div class="col-6">
                                <label for="is_active">Active</label>
                                <select name="is_active" id="is_active" class="form-control">
                                    <option value="1" {{ $student->is_active == 1 ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ $student->is_active == 0 ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label for="is_active">Is Lead</label>
                                <select name="is_lead" id="is_lead" class="form-control">
                                    <option value="1" {{ $student->is_lead == 1 ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ $student->is_lead == 0 ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label for="name" class="mt-3">First Name <span class="text-danger">(Required)</span></label>
                                <input type="text" class="form-control" name="first_name" value="{{$student->first_name}}">
                            </div>
                            <div class="col-6">
                                <label for="name" class="mt-3">Last Name <span class="text-danger">(Required)</span></label>
                                <input type="text" class="form-control" name="last_name" value="{{$student->last_name}}">
                            </div>
                            <div class="col-12">
                                <label for="name" class="mt-3">Company</label>
                                <input type="text" class="form-control" name="company" value="{{$student->company}}">
                            </div>
                            <div class="col-sm-6">
                                <label for="name" class="mt-3">Phone Number</label>
                                <input type="text" class="form-control" name="phone" value="{{$student->phone}}">
                            </div>
                            <div class="col-sm-6">
                                <label for="name" class="mt-3">Email <span class="text-danger">(Required)</span></label>
                                <input type="email" class="form-control" name="email" value="{{$student->email}}">
                            </div>
                            <div class="col-6">
                                <label for="city" class="mt-3">City</label>
                                <input type="text" class="form-control" name="city" value="{{$student->city}}">
                            </div>
                            <div class="col-3">
                                <label for="state" class="mt-3">State</label>
                                <select name="state" id="state" class="form-control">
                                    <option value="#" disabled>Select One</option>
                                    @foreach($states as $state)
                                        <option value="{{$state->code}}" {{ $student->state == $state->code ? 'selected' : '' }}>{{$state->state}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-3">
                                <label for="zip" class="mt-3">Zip</label>
                                <input type="number" class="form-control no-spinners" name="zip" value="{{$student->zip}}">
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="row mt-5">
                            <div class="col-sm-6">
                                <a href="{{route('student.index')}}" class="btn btn-secondary width-100">Cancel</a>
                            </div>
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-primary btn-right width-100">Update {{$student->first_name}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- /Organizations Form -->


    @endsection

    @section('scripts')
        <script>
            $(document).ready(function() {

                $("input[name='phone']").keyup(function() {
                    $(this).val($(this).val().replace(/^(\d{3})(\d{3})(\d+)$/, "$1-$2-$3"));
                });

            });
        </script>

    @endsection

</x-admin-master>
