<x-admin-master>
    @section('page-title') Edit Series | MEX LMS Admin @endsection

    @section('content')
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1 class="m-0">Edit Series</h1>
                    <div>
                        <a href="{{ route('admin.courses.show', $course) }}" class="btn btn-outline-secondary">View</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            @if ($errors->any())
                <div class="card-header">
                    <div class="alert alert-danger mb-0">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="card-body">
                <form method="POST" action="{{ route('admin.courses.update', $course) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Series Title <span class="text-danger">*</span></label>
                            <input name="title" class="form-control" value="{{ old('title',$course->title) }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Active</label>
                            <select name="is_active" class="form-select">
                                <option value="1" {{ old('is_active',$course->is_active) ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ old('is_active',$course->is_active) ? '' : 'selected' }}>No</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Organization <span class="text-danger">*</span></label>
                            <div class="d-flex gap-2">
                                <select name="org_id" class="form-select">
                                    @foreach($organizations as $org)
                                        <option value="{{ $org->id }}"
                                            {{ (int)old('org_id',$course->org_id) === (int)$org->id ? 'selected' : '' }}>
                                            {{ $org->name }}
                                        </option>
                                    @endforeach
                                </select>

                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addOrgModal">
                                    + Add Org
                                </button>
                            </div>
                            <small class="text-muted">Changing this moves the course to a different organization.</small>
                        </div>

                        <div class="col-md-4">
{{--                            <label class="form-label">Position</label>--}}
                            <input type="hidden" name="position" class="form-control"
                                   value="{{ old('position', $course->position) }}" min="0">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Short Description</label>
                            <input name="short_description" class="form-control"
                                   value="{{ old('short_description',$course->short_description) }}">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Long Description</label>
                            <textarea name="long_description" class="form-control" rows="5">{{ old('long_description',$course->long_description) }}</textarea>
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <label class="form-label">Course Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            @if($course->image)
                                <div class="mt-2">
                                    <img src="{{ Storage::url($course->image) }}" alt="{{ $course->image_title ?? 'Course image' }}" style="max-width:240px;height:auto;">
                                </div>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" id="remove_image" name="remove_image" value="1">
                                    <label class="form-check-label" for="remove_image">Remove current image</label>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Image Title (alt text)</label>
                            <input type="text" name="image_title" class="form-control" value="{{ old('image_title', $course->image_title) }}">
                        </div>
                    </div>


                    <div class="mt-4 d-flex gap-2">
                        <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Quick Add Organization Modal --}}
        <div class="modal fade" id="addOrgModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form class="modal-content" method="POST" action="{{ route('admin.organizations.quick-store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Organization</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input name="name" class="form-control" required>
                        <input type="hidden" name="redirect" value="{{ url()->current() }}">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    @endsection
</x-admin-master>
