<x-admin-master>
    @section('page-title') Update: {{ $lesson->title }} | MEX LMS Admin @endsection

    @section('content')
        <h1 class="m-3"><span>Update:</span> {{ $lesson->title }}</h1>

        @if ($errors->any())
            <div class="alert alert-danger mx-3">
                <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success mx-3">{{ session('success') }}</div>
        @endif

        <div class="card shadow mb-4">
            <div class="card-body">

                <div class="row g-3 align-items-end mb-4">

                    <div class="col-md-6">
                        <label class="form-label">Organization <span class="text-danger">(Required)</span></label>
                        <div class="d-block">
                            <select id="orgSelect" class="form-select">
                                <option value="">Select Organization</option>
                                @foreach($organizations as $org)
                                    <option value="{{ $org->id }}"
                                        {{ (int)($selectedOrg ?? 0) === (int)$org->id ? 'selected' : '' }}>
                                        {{ $org->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="mt-1">
                                <a href="#" id="linkAddOrg" class="small text-primary">+ Add Organization</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Series (optional)</label>
                        <div class="d-block">
                            <select name="course_id" id="courseSelect" class="form-select">
                                <option value="">Select Series</option>
                                @foreach($courses as $c)
                                    <option value="{{ $c->id }}" data-org="{{ $c->org_id }}"
                                        {{ (int)optional($selectedCourse)->id === (int)$c->id ? 'selected' : '' }}>
                                        {{ $c->title }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="mt-1">
                                <a href="#" id="linkAddCourse" class="small text-primary">+ Add Series</a>
                            </div>
                        </div>
                    </div>
                </div>

                <form id="lessonForm" action="{{ route('lesson.update', $lesson->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="org_id" id="org_id_hidden" value="{{ $selectedOrg ?? '' }}">
                    <input type="hidden" name="course_id" id="course_id_hidden" value="{{ optional($selectedCourse)->id ?? '' }}">

                    <div class="row">
                        <div class="col-6">
                            <label class="form-label">Instructor (optional)</label>

                            @php
                                $preselectInstructorId = (int)($preselectInstructorId ?? 0);
                                $pre = $preselectInstructorId
                                    ? $instructors->firstWhere('id', $preselectInstructorId)
                                    : null;
                                $preLabel = $pre ? trim(($pre->first_name ?? '').' '.($pre->last_name ?? ''))
                                                 : 'Select instructor (optional)';
                            @endphp

                            <div class="dropdown w-100" id="instructorPicker" data-bs-auto-close="outside">
                                <button class="btn btn-outline-secondary w-100 d-flex justify-content-between align-items-center"
                                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span id="instructorLabel">{{ $preLabel }}</span>
                                    <i class="fa fa-chevron-down ms-2"></i>
                                </button>

                                <div class="dropdown-menu p-2 w-100" style="max-height:320px; overflow:auto;">
                                    <input type="text" class="form-control mb-2" placeholder="Search instructors…" id="instrSearch">
                                    <ul class="list-group list-group-flush" id="instrList">
                                        @foreach($instructors as $ins)
                                            @php $full = trim(($ins->first_name ?? '').' '.($ins->last_name ?? '')); @endphp
                                            <li class="list-group-item list-group-item-action instr-item"
                                                data-id="{{ $ins->id }}"
                                                data-name="{{ $full }}">
                                                <span>{{ $full }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <input type="hidden" name="instructor_id" id="instructor_id" value="{{ $preselectInstructorId ?: '' }}">
                            <button type="button" class="btn btn-link p-0 mt-1" data-bs-toggle="modal" data-bs-target="#addInstructorModal">
                                + Add Instructor
                            </button>
                        </div>

                        <div class="col-6">
                            <label for="is_active">Active</label>
                            <select name="is_active" id="is_active" class="form-control">
                                <option value="1" {{ (int)old('is_active', $lesson->is_active) === 1 ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ (int)old('is_active', $lesson->is_active) === 0 ? 'selected' : '' }}>No</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="mt-3">Course Title <span class="text-danger">(Required)</span></label>
                            <input type="text" class="form-control" name="title" value="{{ old('title', $lesson->title) }}" required>
                        </div>

                        <div class="col-12"><label class="mt-3">Short Description</label>
                            <input type="text" class="form-control" name="short_description" value="{{ old('short_description', $lesson->short_description) }}">
                        </div>
                        <div class="col-12"><label class="mt-3">Long Description</label>
                            <textarea name="long_description" cols="30" rows="5" class="form-control-plaintext">{{ old('long_description', $lesson->long_description) }}</textarea>
                        </div>
                        <div class="col-12"><label class="mt-3">Learning Outcomes</label>
                            <textarea name="learning_outcomes" cols="30" rows="5" class="form-control-plaintext">{{ old('learning_outcomes', $lesson->learning_outcomes) }}</textarea>
                        </div>
                        <div class="col-12"><label class="mt-3">Course Notes</label>
                            <textarea name="course_notes" cols="30" rows="5" class="form-control-plaintext">{{ old('course_notes', $lesson->course_notes) }}</textarea>
                        </div>
                        <div class="col-12"><label class="mt-3">Completion Requirements</label>
                            <textarea name="completion_requirements" cols="30" rows="5" class="form-control-plaintext">{{ old('completion_requirements', $lesson->completion_requirements) }}</textarea>
                        </div>
                        <div class="col-12"><label class="mt-3">Event Link</label>
                            <input type="text" class="form-control" name="event_link" value="{{ old('event_link', $lesson->event_link) }}">
                        </div>

                        <div class="col-6"><label class="mt-3">Total Hours</label>
                            <input type="text" class="form-control" name="total_hours" value="{{ old('total_hours', $lesson->total_hours) }}">
                        </div>
                        <div class="col-6"><label class="mt-3">Total CEU</label>
                            <input type="text" class="form-control" name="total_ceu" value="{{ old('total_ceu', $lesson->total_ceu) }}">
                        </div>

                        <div class="col-4"><label class="mt-3">Student Cost</label>
                            <input type="text" class="form-control" name="student_cost" value="{{ old('student_cost', $lesson->student_cost) }}">
                        </div>
                        <div class="col-4"><label class="mt-3">Platform Cost</label>
                            <input type="text" class="form-control" name="platform_cost" value="{{ old('platform_cost', $lesson->platform_cost) }}">
                        </div>
                        <div class="col-4"><label class="mt-3">Org Net Profit</label>
                            <input type="text" class="form-control" name="pay_to_organization" value="{{ old('pay_to_organization', $lesson->pay_to_organization) }}">
                        </div>

                        <div class="col-6 mt-3">
                            <label>Upload Image</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                            @if ($lesson->image)
                                <div class="mt-2"><img src="{{ asset('storage/'.$lesson->image) }}" style="max-width:300px"></div>
                            @endif
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-sm-6">
                            <a href="{{ route('lesson.index') }}" class="btn btn-secondary width-100">Cancel</a>
                        </div>
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-primary width-100">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Add Organization Modal --}}
        <div class="modal fade" id="addOrgModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form id="quickOrgForm" class="modal-content" method="POST" action="{{ route('admin.organizations.quick-store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Organization</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label class="form-label">Name</label>
                        <input name="name" class="form-control" required>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary">Create</button>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Add Series Modal --}}
        <div class="modal fade" id="addCourseModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form class="modal-content" method="POST" action="{{ route('admin.courses.quick-store') }}">
                    @csrf
                    <div class="modal-header"><h5 class="modal-title">Add Series</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="org_id" id="qc_org_id" value="">
                        <div class="mb-3">
                            <label class="form-label">Series Title</label>
                            <input name="title" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary">Create</button>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Add Instructor Modal --}}
        <div class="modal fade" id="addInstructorModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form id="quickInstructorForm"
                      class="modal-content"
                      method="POST"
                      action="{{ route('admin.instructors.quick-store') }}"
                      data-post-url="{{ route('admin.instructors.quick-store') }}"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Instructor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3"><label class="form-label">First Name *</label>
                            <input name="first_name" class="form-control" required></div>
                        <div class="mb-3"><label class="form-label">Last Name *</label>
                            <input name="last_name" class="form-control" required></div>
                        <div class="mb-3"><label class="form-label">Organization</label>
                            <input name="organization" class="form-control"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Create</button>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    @endsection

    @section('scripts')
        <script>
            /* ---------- helpers ---------- */
            function closeModalById(id) {
                const el = document.getElementById(id);
                if (!el) return;

                // Bootstrap 5 (or 4 via bundle)
                if (window.bootstrap && bootstrap.Modal) {
                    try {
                        var inst = (bootstrap.Modal.getInstance && typeof bootstrap.Modal.getInstance === 'function')
                            ? bootstrap.Modal.getInstance(el)
                            : null;
                        if (!inst) inst = new bootstrap.Modal(el);
                        inst.hide();

                        setTimeout(() => {
                            document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
                            document.body.classList.remove('modal-open');
                        }, 50);
                        return;
                    } catch (e) {
                        // fall through
                    }
                }

                // Bootstrap 4 (jQuery plugin)
                if (window.$ && typeof $(el).modal === 'function') {
                    $(el).modal('hide');
                    setTimeout(() => {
                        $('.modal-backdrop').remove();
                        document.body.classList.remove('modal-open');
                    }, 50);
                    return;
                }

                // Last-resort fallback
                el.classList.remove('show');
                el.style.display = 'none';
                document.body.classList.remove('modal-open');
                document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
            }

            /* ---------- existing selects: org + course ---------- */
            const allCourses   = @json($courses->map(fn($c)=>['id'=>$c->id,'org_id'=>$c->org_id,'title'=>$c->title]));
            const orgSelect    = document.getElementById('orgSelect');
            const courseSelect = document.getElementById('courseSelect');
            const hidOrg       = document.getElementById('org_id_hidden');
            const hidCourse    = document.getElementById('course_id_hidden');

            function refreshCourseOptions(orgId, preselectId = null) {
                if (!courseSelect) return;

                courseSelect.innerHTML = '<option value="">Select Course</option>';

                if (!orgId) {
                    courseSelect.disabled = true;
                    return;
                }

                const options = allCourses.filter(c => String(c.org_id) === String(orgId));
                for (const c of options) {
                    const opt = document.createElement('option');
                    opt.value = c.id;
                    opt.textContent = c.title;
                    if (preselectId && String(preselectId) === String(c.id)) {
                        opt.selected = true;
                    }
                    courseSelect.appendChild(opt);
                }
                courseSelect.disabled = false;
            }

            (function initTopSelectors() {
                const preOrg    = '{{ $selectedOrg ?? '' }}';
                const preCourse = '{{ optional($selectedCourse)->id ?? '' }}';

                if (preOrg) {
                    refreshCourseOptions(preOrg, preCourse || null);
                } else if (courseSelect) {
                    courseSelect.disabled = true;
                }

                if (orgSelect) {
                    orgSelect.addEventListener('change', () => {
                        const v = orgSelect.value;
                        refreshCourseOptions(v, null);
                        if (hidOrg)    hidOrg.value = v;
                        if (hidCourse) hidCourse.value = '';
                        if (courseSelect) courseSelect.value = '';
                    });
                }

                if (courseSelect) {
                    courseSelect.addEventListener('change', () => {
                        if (hidCourse) hidCourse.value = courseSelect.value || '';
                    });
                }
            })();

            /* ---------- open modals (Org / Series) ---------- */
            const linkAddOrg    = document.getElementById('linkAddOrg');
            const linkAddCourse = document.getElementById('linkAddCourse');

            if (linkAddOrg) {
                linkAddOrg.addEventListener('click', e => {
                    e.preventDefault();
                    const modalEl = document.getElementById('addOrgModal');
                    if (!modalEl) return;

                    if (window.bootstrap && bootstrap.Modal) {
                        new bootstrap.Modal(modalEl).show();
                    } else if (window.$ && typeof $('#addOrgModal').modal === 'function') {
                        $('#addOrgModal').modal('show');
                    } else {
                        modalEl.classList.add('show');
                        modalEl.style.display = 'block';
                    }
                });
            }

            if (linkAddCourse) {
                linkAddCourse.addEventListener('click', e => {
                    e.preventDefault();
                    const orgId = orgSelect?.value || '';
                    if (!orgId) {
                        alert('Please choose an Organization first.');
                        return;
                    }

                    const orgInput = document.getElementById('qc_org_id');
                    if (orgInput) orgInput.value = orgId;

                    const modalEl = document.getElementById('addCourseModal');
                    if (!modalEl) return;

                    if (window.bootstrap && bootstrap.Modal) {
                        new bootstrap.Modal(modalEl).show();
                    } else if (window.$ && typeof $('#addCourseModal').modal === 'function') {
                        $('#addCourseModal').modal('show');
                    } else {
                        modalEl.classList.add('show');
                        modalEl.style.display = 'block';
                    }
                });
            }

            /* ---------- quick-add organization (AJAX) ---------- */
            const quickOrgForm = document.getElementById('quickOrgForm');
            if (quickOrgForm) {
                quickOrgForm.addEventListener('submit', async ev => {
                    ev.preventDefault();

                    const url = @json(route('admin.organizations.quick-store'));
                    const fd  = new FormData(quickOrgForm); // includes CSRF

                    try {
                        const res = await fetch(url, {
                            method: 'POST',
                            body: fd,
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                        });

                        const ct = res.headers.get('content-type') || '';
                        if (!ct.includes('application/json')) {
                            const text = await res.text();
                            console.error('Expected JSON, got:', text.slice(0, 400));
                            throw new Error('Server returned non-JSON response.');
                        }

                        const json = await res.json();
                        if (!json.ok || !json.org) {
                            throw new Error(json.message || 'Failed to create organization.');
                        }

                        // Close the modal
                        closeModalById('addOrgModal');

                        // Reload this page keeping course + instructor if set
                        const params   = new URLSearchParams(window.location.search);
                        const courseId = courseSelect?.value || '';
                        const instrId  = document.getElementById('instructor_id')?.value || '';

                        params.set('org', json.org.id);
                        if (courseId) params.set('course', courseId);
                        if (instrId)  params.set('instructor_id', instrId);

                        window.location.search = params.toString();
                    } catch (err) {
                        alert(err.message || 'Error creating organization.');
                    }
                });
            }

            /* ---------- instructor search & pick (dropdown) ---------- */
            (function initInstructorPicker() {
                const ddBtn       = document.querySelector('#instructorPicker [data-bs-toggle="dropdown"]');
                const instrSearch = document.getElementById('instrSearch');
                const instrList   = document.getElementById('instrList');
                const instrLabel  = document.getElementById('instructorLabel');
                const instrIdInp  = document.getElementById('instructor_id');

                if (!instrList || !instrLabel || !instrIdInp) return;

                const items = () => Array.from(instrList.querySelectorAll('.instr-item') || []);

                const strip = s => (s || '')
                    .toLowerCase()
                    .normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '')
                    .replace(/\s+/g, ' ')
                    .trim();

                function matches(name, query) {
                    const words  = strip(name).split(' ');
                    const tokens = strip(query).split(' ').filter(Boolean);
                    if (!tokens.length) return true;
                    return tokens.every(t => words.some(w => w.startsWith(t)));
                }

                function filter() {
                    const q = instrSearch ? instrSearch.value : '';
                    items().forEach(li => {
                        const name = li.dataset.name || '';
                        li.style.display = matches(name, q) ? '' : 'none';
                    });
                }

                if (ddBtn && window.bootstrap && bootstrap.Dropdown) {
                    ddBtn.addEventListener('shown.bs.dropdown', () => {
                        if (instrSearch) instrSearch.focus();
                    });
                }

                if (instrSearch) {
                    instrSearch.addEventListener('input', filter);
                }

                instrList.addEventListener('click', e => {
                    const li = e.target.closest('.instr-item');
                    if (!li) return;

                    const id   = li.dataset.id || '';
                    const name = li.dataset.name || 'Select instructor (optional)';

                    instrIdInp.value       = id;
                    instrLabel.textContent = name;

                    try {
                        if (ddBtn && window.bootstrap && bootstrap.Dropdown) {
                            bootstrap.Dropdown.getOrCreateInstance(ddBtn).hide();
                        } else if (ddBtn && window.$ && typeof $(ddBtn).dropdown === 'function') {
                            $(ddBtn).dropdown('toggle');
                        }
                    } catch (_) {}
                });

                filter();
            })();

            /* ---------- quick-add instructor (AJAX) ---------- */
            (function () {
                const form      = document.getElementById('quickInstructorForm');
                const list      = document.getElementById('instrList');
                const searchBox = document.getElementById('instrSearch');
                const label     = document.getElementById('instructorLabel');
                const hiddenId  = document.getElementById('instructor_id');

                if (!form || !list || !label || !hiddenId) return;

                function prependInstructor(id, name) {
                    const li = document.createElement('li');
                    li.className    = 'list-group-item list-group-item-action instr-item';
                    li.dataset.id   = id;
                    li.dataset.name = name;
                    li.innerHTML    = `<span>${name}</span>`;
                    list.prepend(li);
                }

                async function handleSubmit(e) {
                    e.preventDefault();

                    const submitBtn = form.querySelector('[type="submit"]');
                    if (submitBtn) submitBtn.disabled = true;

                    try {
                        const url = form.getAttribute('action') || form.dataset.postUrl;
                        const fd  = new FormData(form); // includes @csrf

                        const res = await fetch(url, {
                        method: 'POST',
                            body: fd,
                            headers: {
                            'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const ct = res.headers.get('content-type') || '';
                    if (!ct.includes('application/json')) {
                        const text = await res.text();
                        console.error('Expected JSON, got:', text.slice(0, 400));
                        throw new Error('Server returned non-JSON response.');
                    }

                    const json = await res.json();
                    if (!json.ok || !json.instructor) {
                        throw new Error(json.message || 'Failed to create instructor.');
                    }

                    const ins  = json.instructor;
                    const name = ins.name || [ins.first_name, ins.last_name].filter(Boolean).join(' ').trim();

                    // Add to the list & select it
                    prependInstructor(ins.id, name);
                    hiddenId.value    = ins.id;
                    label.textContent = name;

                    // ✅ Close modal using our helper (works BS4/BS5)
                    closeModalById('addInstructorModal');

                    // Reset form + search
                    form.reset();
                    if (searchBox) {
                        searchBox.value = '';
                        searchBox.dispatchEvent(new Event('input', { bubbles: true }));
                    }
                } catch (err) {
                    alert(err.message || 'Error creating instructor.');
                } finally {
                    if (submitBtn) submitBtn.disabled = false;
                }
            }

            form.addEventListener('submit', handleSubmit);
            })();
        </script>
    @endsection


</x-admin-master>
