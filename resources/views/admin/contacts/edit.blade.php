<x-admin-master>

    @section('page-title')
        Update: {{$contact->first_name}} {{$contact->last_name}} | MEX LMS Admin
    @endsection

    @section('favicon')
        {{asset('/storage/app-images/favicon.png')}}
    @endsection

    @section('styles')

    @endsection

    @section('content')
        <h1 class="m-3"><span>Update:</span> {{$contact->first_name}} {{$contact->last_name}}</h1>


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
                <form action="{{ route('contact.update', $contact->id) }}" method="post">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <div class="row">
                            <div class="col-6">
                                <label for="is_active">Active</label>
                                <select name="is_active" id="is_active" class="form-control">
                                    <option value="1" {{ $contact->is_active == 1 ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ $contact->is_active == 0 ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label for="is_active">Is Lead</label>
                                <select name="is_lead" id="is_lead" class="form-control">
                                    <option value="1" {{ $contact->is_lead == 1 ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ $contact->is_lead == 0 ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label for="name" class="mt-3">First Name <span class="text-danger">(Required)</span></label>
                                <input type="text" class="form-control" name="first_name" value="{{$contact->first_name}}">
                            </div>
                            <div class="col-6">
                                <label for="name" class="mt-3">Last Name <span class="text-danger">(Required)</span></label>
                                <input type="text" class="form-control" name="last_name" value="{{$contact->last_name}}">
                            </div>
                            <div class="col-12">
                                <label for="name" class="mt-3">Organization</label>
                                <input type="text" class="form-control" name="organization" id="organization" value="{{$contact->organization}}">
                            </div>
                            <div class="col-sm-6">
                                <label for="name" class="mt-3">Phone Number</label>
                                <input type="text" class="form-control" name="phone" value="{{$contact->phone}}">
                            </div>
                            <div class="col-sm-6">
                                <label for="name" class="mt-3">Email</label>
                                <input type="email" class="form-control" name="email" value="{{$contact->email}}">
                            </div>

                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-sm-6">
                            <a href="{{route('instructor.index')}}" class="btn btn-secondary width-100">Cancel</a>
                        </div>
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-primary btn-right width-100">Update {{$contact->first_name}}</button>
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
