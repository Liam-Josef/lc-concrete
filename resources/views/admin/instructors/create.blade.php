<x-admin-master>

    @section('page-title')
        Add Instructor | MEX LMS Admin
    @endsection

    @section('favicon')
        {{asset('/storage/app-images/favicon.png')}}
    @endsection

    @section('styles')

    @endsection

    @section('content')
        <h1 class="m-3"><span>Add</span> an Instructor</h1>


        <!-- Instructor Form -->
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
                <form action="{{ route('instructor.store') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <div class="row">
                            <div class="col-6">
                                <label for="is_active">Active</label>
                                <select name="is_active" id="is_active" class="form-control">
                                    <option value="1" selected>Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>

                            <div class="col-6"></div>

                            <div class="col-6">
                                <label for="first_name" class="mt-3">First Name <span class="text-danger">(Required)</span></label>
                                <input type="text" class="form-control" name="first_name" value="{{ old('first_name') }}">
                            </div>

                            <div class="col-6">
                                <label for="last_name" class="mt-3">Last Name <span class="text-danger">(Required)</span></label>
                                <input type="text" class="form-control" name="last_name" value="{{ old('last_name') }}">
                            </div>

                            <div class="col-12">
                                <label for="org_id" class="mt-3">Organization</label>
                                <input type="text" class="form-control" name="organization" value="{{ old('organization') }}">
{{--                                <select name="org_id" id="org_id" class="form-control">--}}
{{--                                    <option value="">Select Organization</option>--}}
{{--                                    @foreach ($organizations as $organization)--}}
{{--                                        <option value="{{ $organization->id }}" {{ old('org_id') == $organization->id ? 'selected' : '' }}>--}}
{{--                                            {{ $organization->name }}--}}
{{--                                        </option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
                            </div>

                            <div class="col-sm-6">
                                <label for="phone" class="mt-3">Phone Number</label>
                                <input type="text" class="form-control" name="phone" value="{{ old('phone') }}">
                            </div>

                            <div class="col-sm-6">
                                <label for="email" class="mt-3">Email <span class="text-danger">(Required)</span></label>
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                            </div>

                            <div class="col-12">
                                <label for="bio" class="mt-3">Bio</label>
                                <textarea name="bio" id="bio" cols="30" rows="10" class="form-control">{{ old('bio') }}</textarea>
                            </div>

                            <div class="col-sm-6">
                                <label for="image" class="mt-3">Upload Image</label>
                                <input type="file" class="form-control" name="image" accept="image/*">
                            </div>
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="col-sm-6">
                            <a href="{{route('instructor.index')}}" class="btn btn-secondary width-100">Cancel</a>
                        </div>
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-primary width-100">Add Instructor</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
        <!-- /Instructor Form -->


    @endsection

    @section('scripts')
        <script>
            $(document).ready(function() {

                $("input[name='phone']").keyup(function() {
                    $(this).val($(this).val().replace(/^(\d{3})(\d{3})(\d+)$/, "$1-$2-$3"));
                });

            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const isLead = document.getElementById('is_lead');
                const orgInput = document.getElementById('organization_input');
                const orgSelect = document.getElementById('organization_select');

                function toggleOrganizationField() {
                    if (isLead.value == '1') {
                        orgInput.style.display = 'block';
                        orgSelect.style.display = 'none';
                    } else {
                        orgInput.style.display = 'none';
                        orgSelect.style.display = 'block';
                    }
                }

                // Initial load
                toggleOrganizationField();

                // On change
                isLead.addEventListener('change', toggleOrganizationField);
            });
        </script>

    @endsection

</x-admin-master>
