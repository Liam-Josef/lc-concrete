<x-admin-master>

    @section('page-title')
        Update: {{$organization->name}} | MEX LMS Admin
    @endsection

    @section('favicon')
        {{asset('/storage/app-images/favicon.png')}}
    @endsection

    @section('styles')

    @endsection

    @section('content')
        <h1 class="m-3"><span>Update:</span> {{$organization->name}}</h1>


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
                <form action="{{ route('organization.update', $organization->id) }}" method="post">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <div class="row">
                            <div class="col-6">
                                <label for="is_active">Active</label>
                                <select name="is_active" id="is_active" class="form-control">
                                    <option value="1" {{ $organization->is_active == 1 ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ $organization->is_active == 0 ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label for="mex_association">MEX Association <span class="text-danger">(Required)</span></label>
                                <select name="mex_association" id="mex_association" class="form-control">
                                    <option value="#">Select One</option>
                                    <option value="1" {{ $organization->mex_association == 1 ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ $organization->mex_association == 0 ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="name" class="mt-3">Organization Name <span class="text-danger">(Required)</span></label>
                                <input type="text" class="form-control" name="name" value="{{$organization->name}}">
                            </div>
                            <div class="col-sm-6">
                                <label for="name" class="mt-3">Phone Number</label>
                                <input type="text" class="form-control" name="phone" value="{{$organization->phone}}">
                            </div>
                            <div class="col-sm-6">
                                <label for="name" class="mt-3">Organization Email <span class="text-danger">(Required)</span></label>
                                <input type="email" class="form-control" name="email" value="{{$organization->email}}">
                            </div>
                            <div class="col-12">
                                <label for="name" class="mt-3">Website</label>
                                <input type="text" class="form-control" name="website" value="{{$organization->website}}">
                            </div>
                            <div class="col-12">
                                <label for="name" class="mt-3">Address Line 1</label>
                                <input type="text" class="form-control" name="address_1" value="{{$organization->address_1}}">
                            </div>
                            <div class="col-12">
                                <label for="name" class="mt-3">Address Line 2</label>
                                <input type="text" class="form-control" name="address_2" value="{{$organization->address_2}}">
                            </div>
                            <div class="col-sm-7">
                                <label for="city" class="mt-3">City <span class="text-danger">(Required)</span></label>
                                <input type="text" class="form-control" name="city" value="{{$organization->city}}">
                            </div>
                            <div class="col-sm-3">
                                <label for="state" class="mt-3">State <span class="text-danger">(Required)</span></label>
                                <select name="state" id="state" class="form-control">
                                    <option value="#" disabled>Select One</option>
                                    @foreach($states as $state)
                                        <option value="{{$state->code}}" {{ $organization->state == $state->code ? 'selected' : '' }}>{{$state->state}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <label for="zip" class="mt-3">Zip Code</label>
                                <input type="number" class="form-control no-spinners" name="zip" value="{{$organization->zip}}">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-sm-6">
                            <a href="{{route('organization.index')}}" class="btn btn-secondary width-100">Cancel</a>
                        </div>
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-primary width-100">Add Org</button>
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
