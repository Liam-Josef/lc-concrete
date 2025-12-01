<x-admin-master>
    @section('page-title')
        Create Page | MEX Learning Admin
    @endsection

    @section('favicon')
        {{asset('/storage/app-images/favicon.png')}}
    @endsection

    @section('content')
        <h1 class="h3 m-3 text-gray-800">Create Page</h1>

        <div class="row">
            <div class="col-lg-12">
                <form method="post" enctype="multipart/form-data" action="{{ route('utilities.page_store') }}">
                    @csrf

                    <div class="mb-3">
                        <label>Route name</label>
                        <input class="form-control" name="route_name" value="{{ old('route_name') }}" required>
                        <small>e.g. <code>courses.index</code> (must match your <code>route()</code> name)</small>
                    </div>

                    <div class="mb-3">
                        <label>Slug</label>
                        <input class="form-control" name="slug" value="{{ old('slug') }}" required>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label>Page Title (&lt;title&gt;)</label>
                            <input class="form-control" name="title" value="{{ old('title') }}">
                        </div>
                        <div class="col-md-6">
                            <label>H1</label>
                            <input class="form-control" name="h1" value="{{ old('h1') }}">
                        </div>
                    </div>

                    <div class="mb-3 mt-3">
                        <label>Meta Description</label>
                        <textarea class="form-control" name="meta_description">{{ old('meta_description') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label>Meta Keywords</label>
                        <textarea class="form-control" name="meta_keywords">{{ old('meta_keywords') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label>Banner Image</label>
                        <input type="file" class="form-control" name="banner_image" accept="image/*">
                    </div>

                    <div class="mb-3">
                        <label>Extra Settings (JSON)</label>
                        <textarea
                            class="form-control"
                            id="settings_json"
                            name="settings_json"
                            rows="6"
                        >{{ old('settings_json', "{}") }}</textarea>
                        <small class="text-muted">Example: {"hero_subtitle":"Welcome","cta":{"text":"Buy","url":"/buy"}}</small>
                        <div id="settings_json_error" class="text-danger small d-none mt-1"></div>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label">Active</label>
                    </div>

                    <button class="btn btn-primary">Create</button>
                    <a class="btn btn-secondary" href="{{ route('utilities.page_index') }}">Cancel</a>
                </form>
            </div>
        </div>
    @endsection

    @section('scripts')
        <script>
            (function(){
                const ta  = document.getElementById('settings_json');
                const err = document.getElementById('settings_json_error');
                if (!ta || !err) return;

                function validate(prettyPrint = false) {
                    const val = ta.value.trim();
                    if (val === '') {
                        err.classList.add('d-none');
                        err.textContent = '';
                        return true;
                    }
                    try {
                        const parsed = JSON.parse(val);
                        if (prettyPrint) ta.value = JSON.stringify(parsed, null, 2);
                        err.classList.add('d-none');
                        err.textContent = '';
                        return true;
                    } catch (e) {
                        err.classList.remove('d-none');
                        err.textContent = 'Invalid JSON: ' + e.message;
                        return false;
                    }
                }

                ta.addEventListener('input', () => validate(false));

                const form = ta.closest('form');
                if (form) {
                    form.addEventListener('submit', (ev) => {
                        if (!validate(true)) {
                            ev.preventDefault();
                            ta.focus();
                        }
                    });
                }
            })();
        </script>
    @endsection
</x-admin-master>
