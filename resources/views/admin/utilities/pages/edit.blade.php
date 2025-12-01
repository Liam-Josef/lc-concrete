<x-admin-master>

    @section('page-title')
        Pages | MEX Learning Admin
    @endsection

    @section('favicon')
        {{asset('/storage/app-images/favicon.png')}}
    @endsection

    @section('content')

        <h1 class="h3 m-3 text-gray-800">MEX Pages</h1>

        <div class="row text-center">
            <form method="post" enctype="multipart/form-data" action="{{ route('utilities.page_update', $page) }}">
                @csrf @method('PUT')

                <div class="mb-3">
                    <label>Route name</label>
                    <input class="form-control" name="route_name" value="{{ old('route_name',$page->route_name) }}" required>
                    <small>e.g. courses.index (matches your route())</small>
                </div>

                <div class="mb-3">
                    <label>Slug</label>
                    <input class="form-control" name="slug" value="{{ old('slug',$page->slug) }}" required>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label>Page Title (&lt;title&gt;)</label>
                        <input class="form-control" name="title" value="{{ old('title',$page->title) }}">
                    </div>
                    <div class="col-md-6">
                        <label>H1</label>
                        <input class="form-control" name="h1" value="{{ old('h1',$page->h1) }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label>Meta Description</label>
                    <textarea class="form-control" name="meta_description">{{ old('meta_description',$page->meta_description) }}</textarea>
                </div>

                <div class="mb-3">
                    <label>Meta Keywords</label>
                    <textarea class="form-control" name="meta_keywords">{{ old('meta_keywords',$page->meta_keywords) }}</textarea>
                </div>

                <div class="mb-3">
                    <label>Banner Image</label>
                    <input type="file" class="form-control" name="banner_image" accept="image/*">
                    @if($page->banner_url)
                        <img src="{{ $page->banner_url }}" alt="" class="mt-2" style="max-height:120px">
                    @endif
                </div>

                <div class="mb-3">
                    <label>Extra Settings (JSON)</label>
                    <textarea
                        class="form-control"
                        id="settings_json"
                        name="settings_json"
                        rows="6"
                    >{{ old('settings_json', json_encode($page->settings ?? new \stdClass(), JSON_PRETTY_PRINT)) }}</textarea>
                    <small class="text-muted">Paste valid JSON (e.g. {"hero_subtitle":"Welcome","cta":{"text":"Buy","url":"/buy"}})</small>
                    <div id="settings_json_error" class="text-danger small d-none mt-1"></div>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active',$page->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label">Active</label>
                </div>

                <button class="btn btn-primary">Save</button>
            </form>

        </div>

    @endsection


    @section('scripts')
            <script>
                (function(){
                    const ta = document.getElementById('settings_json');
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
                            if (prettyPrint) {
                                ta.value = JSON.stringify(parsed, null, 2);
                            }
                            err.classList.add('d-none');
                            err.textContent = '';
                            return true;
                        } catch (e) {
                            err.classList.remove('d-none');
                            err.textContent = 'Invalid JSON: ' + e.message;
                            return false;
                        }
                    }

                    // Validate as you type
                    ta.addEventListener('input', () => validate(false));

                    // Pretty-print & validate on submit
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
