<x-admin-master>
    @section('page-title') Add Series | Step 1 @endsection

    @section('content')
            <div class="row">
                <div class="col-12">
                    <h1 class="mb-3">Add Series</h1>

                    <div class="progress mb-3" style="height:10px;">
                        <div class="progress-bar" role="progressbar" style="width:50%"></div>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mb-4">
                        <span>Step 1: Choose Organization</span>
                        <span>Step 2: Series Details</span>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger"><ul class="mb-0">
                                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                            </ul></div>
                    @endif
                </div>
            </div>

        <div class="card shadow">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.courses.create.post') }}">
                    @csrf
                    <div class="row g-3 align-items-end">
                        <div class="col-md-8">
                            <label class="form-label">Organization</label>
                            <select name="org_id" class="form-select" required>
                                <option value="" disabled {{ empty($selectedOrg) ? 'selected' : '' }}>Choose…</option>
                                @foreach($orgs as $o)
                                    <option value="{{ $o->id }}" {{ (int)$selectedOrg === $o->id ? 'selected' : '' }}>
                                        {{ $o->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <button class="btn btn-primary">Next</button>
                            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#addOrgModal">
                                Add Organization
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Add Organization modal --}}
        <div class="modal fade" id="addOrgModal" tabindex="-1">
            <div class="modal-dialog">
                <form class="modal-content" method="POST" action="{{ route('admin.organizations.quick-store') }}">
                    @csrf
                    <div class="modal-header"><h5 class="modal-title">Add Organization</h5></div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input name="name" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary">Create</button>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    @endsection
</x-admin-master>
