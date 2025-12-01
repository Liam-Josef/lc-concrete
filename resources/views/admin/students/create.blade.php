<x-admin-master>

    @section('page-title')
        Add Student | MEX LMS Admin
    @endsection

    @section('favicon')
        {{asset('/storage/app-images/favicon.png')}}
    @endsection

    @section('styles')

    @endsection

    @section('content')
        <h1 class="m-3"><span>Add</span> a Student</h1>


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
                <form action="{{ route('student.store') }}" method="post">
                    @csrf
                    @method('POST')

                    <div class="form-group">
                        <div class="row">
                            <div class="col-6">
                                <label for="is_active">Active</label>
                                <select name="is_active" id="is_active" class="form-control">
                                    <option value="1" selected>Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label for="is_lead">Is Lead</label>
                                <select name="is_lead" id="is_lead" class="form-control">
                                    <option value="1">Yes</option>
                                    <option value="0" selected>No</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label for="first_name" class="mt-3">First Name <span class="text-danger">(Required)</span></label>
                                <input type="text" class="form-control" name="first_name">
                            </div>
                            <div class="col-6">
                                <label for="last_name" class="mt-3">Last Name <span class="text-danger">(Required)</span></label>
                                <input type="text" class="form-control" name="last_name">
                            </div>
                            <div class="col-12">
                                <label for="company" class="mt-3">Company</label>
                                <input type="text" class="form-control" name="company">
                            </div>
                            <div class="col-sm-6">
                                <label for="phone" class="mt-3">Phone Number</label>
                                <input type="text" class="form-control" name="phone">
                            </div>
                            <div class="col-sm-6">
                                <label for="email" class="mt-3">Email <span class="text-danger">(Required)</span></label>
                                <input type="email" class="form-control" name="email">
                            </div>
                            <div class="col-sm-12">
                                <label for="address_1" class="mt-3">Address</label>
                                <input type="text" class="form-control" name="address_1">
                            </div>
                            <div class="col-sm-12">
                                <label for="address_2" class="mt-3">Address Line 2</label>
                                <input type="text" class="form-control" name="address_2">
                            </div>
                            <div class="col-6">
                                <label for="city" class="mt-3">City</label>
                                <input type="text" class="form-control" name="city">
                            </div>
                            <div class="col-3">
                                <label for="state" class="mt-3">State</label>
                                <select name="state" id="state" class="form-control">
                                    <option value="#" disabled>Select One</option>
                                    @foreach($states as $state)
                                        <option value="{{$state->code}}">{{$state->state}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-3">
                                <label for="zip" class="mt-3">Zip</label>
                                <input type="number" class="form-control no-spinners" name="zip">
                            </div>

                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-sm-6">
                            <a href="{{route('student.index')}}" class="btn btn-secondary width-100">Cancel</a>
                        </div>
                        <div class="col-sm-6">
                            <input type="hidden" name="ip" value="{{ request()->ip() }}">
                            <button type="submit" class="btn btn-primary btn-right width-100">Add Student</button>
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
