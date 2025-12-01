<x-admin-master>
    @section('page-title') Add Series | Step 2 | MEX LMS Admin@endsection

        @section('content')
            <div class="row">
                <div class="col-12">
                    <h1 class="mb-3">Add Series</h1>

                    <div class="progress mb-3" style="height:10px;">
                        <div class="progress-bar" role="progressbar" style="width:100%"></div>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mb-4">
                        <span>Step 1: Choose Organization</span>
                        <span>Step 2: Series Details</span>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card shadow">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-8">
                            <h4>
                                <strong>Organization:</strong> {{ $org->name }}
                                @if(!empty($org->city) || !empty($org->state))
                                    <span class="text-muted">— {{ $org->city ?? '' }} {{ $org->state ?? '' }}</span>
                                @endif
                            </h4>
                        </div>
                        <div class="col-sm-4">
                            <a class="btn btn-sm btn-primary btn-right btn-100"
                               href="{{ route('admin.courses.create', ['org' => $org->id]) }}">
                                Change Organization
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.courses.store') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="org_id" value="{{ $org->id }}">

                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label">Series Title <span class="text-danger">*</span></label>
                                <input name="title" class="form-control" value="{{ old('title') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Active</label>
                                <select name="is_active" class="form-select">
                                    <option value="1" selected>Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Short Description</label>
                                <input
                                    name="short_description"
                                    id="short_description"
                                    class="form-control js-charcount"
                                    maxlength="255"
                                    value="{{ old('short_description', $course->short_description ?? '') }}"
                                    aria-describedby="shortDescHelp"
                                    data-count-target="#shortDescCount"
                                >
                                <small id="shortDescHelp" class="text-muted">
                                    <span id="shortDescCount">255</span> / 255 characters left
                                </small>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Long Description</label>
                                <textarea name="long_description" class="form-control" rows="4">{{ old('long_description') }}</textarea>
                            </div>
                        </div>

                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label class="form-label">Course Image</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Image Title (alt text)</label>
                                <input type="text" name="image_title" class="form-control" value="{{ old('image_title') }}">
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-12">
                                <div class="mt-4 d-flex gap-2">
                                    <a href="{{ route('admin.courses.create', ['org' => $org->id]) }}" class="btn btn-outline-secondary">Back</a>
                                    <button class="btn btn-primary">Create Series</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endsection

    @section('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const fields = document.querySelectorAll('.js-charcount');

                    fields.forEach(function (el) {
                        const max = parseInt(el.getAttribute('maxlength') || '255', 10);
                        const targetSel = el.getAttribute('data-count-target');
                        const counterEl = targetSel ? document.querySelector(targetSel) : null;
                        const helpEl = counterEl ? counterEl.closest('small') : null;

                        function update() {
                            const remaining = Math.max(0, max - (el.value || '').length);
                            if (counterEl) counterEl.textContent = remaining;

                            // nice visual cue near the limit
                            if (helpEl) {
                                if (remaining <= 20) {
                                    helpEl.classList.remove('text-muted');
                                    helpEl.classList.add('text-danger');
                                } else {
                                    helpEl.classList.add('text-muted');
                                    helpEl.classList.remove('text-danger');
                                }
                            }
                        }

                        // initialize & bind
                        update();
                        el.addEventListener('input', update);
                    });
                });
            </script>

        @endsection
</x-admin-master>
