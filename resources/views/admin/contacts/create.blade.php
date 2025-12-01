<x-admin-master>

    @section('page-title')
        Add Contact | MEX LMS Admin
    @endsection

    @section('styles')

    @endsection

    @section('content')
        <h1 class="m-3"><span>Add</span> a Contact</h1>


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
                <form action="{{ route('contact.store') }}" method="post">
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
                                <select name="is_lead" id="is_lead">
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
                                <label for="name" class="mt-3">Organization</label>
                                <input type="text" class="form-control" name="organization" id="organization_input" style="display: none;">
                                <select name="organization_id" id="organization_select" class="form-control">
                                    <option value="">Select Organization</option>
                                    @foreach ($organizations as $organization)
                                        <option value="{{ $organization->id }}">{{ $organization->name }}</option>
                                    @endforeach
                                </select>
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
                    <div class="row mt-5">
                        <div class="col-sm-6">
                            <a href="{{route('contact.index')}}" class="btn btn-secondary width-100">Cancel</a>
                        </div>
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-primary width-100">Add Contact</button>
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
