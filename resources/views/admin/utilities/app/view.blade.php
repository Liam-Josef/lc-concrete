<x-admin-master>
    @section('page-title')
        Dashboard | {{$settings->app_name}} Admin
    @endsection
    @section('favicon', asset('/storage/app-images/favicon.png'))

    @section('content')
        <h1 class="h3 m-3 text-gray-800">App Info</h1>

        @if(session('success'))
            <div class="alert alert-success mx-3">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger mx-3">
                <ul class="mb-0">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @php($s = $settings ?? $app ?? null)

        <form class="card mx-3 mb-5" method="POST" action="{{ route('admin.app.update') }}" enctype="multipart/form-data" id="appForm">
            @csrf
            @method('PUT')

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">App Name *</label>
                        <input type="text"
                               name="app_name"
                               class="form-control"
                               value="{{ old('app_name', $s->app_name ?? '') }}">
                        @error('app_name')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Company URL</label>
                        <input type="text" name="company_url" class="form-control" value="{{ old('company_url', $s->company_url ?? '') }}">
                        @error('company_url')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Company Phone</label>
                        <input type="text" name="company_phone" class="form-control" value="{{ old('company_phone', $s->company_phone ?? '') }}">
                        @error('company_phone')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Company Email</label>
                        <input type="email" name="company_email" class="form-control" value="{{ old('company_email', $s->company_email ?? '') }}">
                        @error('company_email')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- images --}}
                    <div class="col-md-6">
                        <label class="form-label">Logo</label>
                        <input type="file" name="logo" class="form-control">
                        @if(!empty($s?->logo))
                            <div class="mt-2"><img src="{{ asset('storage/'.$s->logo) }}" alt="logo" style="max-height:60px;"></div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Favicon</label>
                        <input type="file" name="favicon" class="form-control">
                        @if(!empty($s?->favicon))
                            <div class="mt-2"><img src="{{ asset('storage/'.$s->favicon) }}" alt="favicon" style="max-height:30px;"></div>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Home Background</label>
                        <input type="file" name="home_background" class="form-control">
                        @if(!empty($s?->home_background))
                            <div class="mt-2"><img src="{{ asset('storage/'.$s->home_background) }}" alt="home" style="max-height:90px;"></div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Internal Background</label>
                        <input type="file" name="internal_background" class="form-control">
                        @if(!empty($s?->internal_background))
                            <div class="mt-2"><img src="{{ asset('storage/'.$s->internal_background) }}" alt="internal" style="max-height:90px;"></div>
                        @endif
                    </div>

                    {{-- Executive Director --}}
                    <div class="col-md-6">
                        <label class="form-label">Executive Director Name</label>
                        <input type="text" name="exec_director_name" class="form-control" value="{{ old('exec_director_name', $s->exec_director_name ?? '') }}">
                        @error('exec_director_name')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Executive Director Signature</label>

                            <div class="border rounded p-2 bg-white">
                                {{-- Important: give the canvas a visible height --}}
                                <canvas id="sigCanvas" class="w-100" style="height:180px; touch-action:none;"></canvas>
                            </div>

                            <div class="mt-2 d-flex gap-2">
                                <button type="button" id="sigClear" class="btn btn-sm btn-outline-secondary">Clear</button>
                            </div>

                            {{-- Hidden field to carry the PNG (data URL) to the server on submit --}}
                            <input type="hidden" name="exec_director_signature_data" id="sigData">
                        </div>

                    </div>

                    {{-- Analytics --}}
                    <div class="col-md-6">
                        <label class="form-label">GA4 Measurement ID (e.g., G-XXXXXXX)</label>
                        <input type="text" name="ga_measurement_id" class="form-control" value="{{ old('ga_measurement_id', $s->ga_measurement_id ?? '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">GTM Container ID (e.g., GTM-XXXXXX)</label>
                        <input type="text" name="gtm_container_id" class="form-control" value="{{ old('gtm_container_id', $s->gtm_container_id ?? '') }}">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Additional Analytics Scripts</label>
                        <div id="scriptsWrap">
                            @php($scripts = old('analytics_scripts', $s->analytics_scripts ?? []))
                            @forelse($scripts as $idx => $snip)
                                <div class="mb-2 d-flex align-items-start gap-2">
                                    <textarea name="analytics_scripts[]" class="form-control" rows="3">{{ $snip }}</textarea>
                                    <button type="button" class="btn btn-outline-danger remove-script">×</button>
                                </div>
                            @empty
                                <div class="mb-2 d-flex align-items-start gap-2">
                                    <textarea name="analytics_scripts[]" class="form-control" rows="3" placeholder="<script>/* … */</script>"></textarea>
                                    <button type="button" class="btn btn-outline-danger remove-script">×</button>
                                </div>
                            @endforelse
                        </div>
                        <button type="button" id="addScript" class="btn btn-sm btn-outline-primary">+ Add Script</button>
                    </div>

                    {{-- Accreditation --}}
                    <div class="col-md-6">
                        <label class="form-label">Accreditation Image</label>
                        <input type="file" name="accreditation_image" class="form-control">
                        @if(!empty($s?->accreditation_image))
                            <div class="mt-2"><img src="{{ asset('storage/'.$s->accreditation_image) }}" alt="accred" style="max-height:60px;"></div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Accreditation Image Alt</label>
                        <input type="text" name="accreditation_image_alt" class="form-control" value="{{ old('accreditation_image_alt', $s->accreditation_image_alt ?? '') }}">
                    </div>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Save Settings</button>
            </div>
        </form>
    @endsection

    @section('scripts')
        <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const canvas = document.getElementById('sigCanvas');
                const sigPad = new SignaturePad(canvas, { minWidth: 0.8, maxWidth: 2.6, penColor: '#111' });

                function resizeCanvas() {
                    const ratio = Math.max(window.devicePixelRatio || 1, 1);
                    const cssW = canvas.offsetWidth;
                    const cssH = parseInt(getComputedStyle(canvas).height, 10);
                    const data = sigPad.toData();

                    canvas.width  = cssW * ratio;
                    canvas.height = cssH * ratio;
                    const ctx = canvas.getContext('2d');
                    ctx.setTransform(ratio, 0, 0, ratio, 0, 0);

                    sigPad.clear();
                    if (data && data.length) sigPad.fromData(data);
                }

                resizeCanvas();
                window.addEventListener('resize', resizeCanvas);

                // ✅ PRELOAD existing signature if we have one
                @if (!empty($s?->exec_director_signature_path))
                // Option A: let SignaturePad render the saved PNG (keeps scaling logic simple)
                sigPad.fromDataURL(
                    "{{ Storage::url($s->exec_director_signature_path) }}",
                    { ratio: Math.max(window.devicePixelRatio || 1, 1), width: canvas.width, height: canvas.height }
                );


                @endif

                document.getElementById('sigClear').addEventListener('click', () => sigPad.clear());

                // push PNG data URL on submit (only if user actually signed)
                const form = document.getElementById('appForm');
                form.addEventListener('submit', function () {
                    if (!sigPad.isEmpty()) {
                        document.getElementById('sigData').value = sigPad.toDataURL('image/png');
                    }
                });
            });
        </script>
    @endsection

</x-admin-master>
