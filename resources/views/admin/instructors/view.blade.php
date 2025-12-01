{{-- resources/views/admin/instructors/view.blade.php --}}
<x-admin-master>
    @section('page-title') {{ $instructor->first_name }} {{ $instructor->last_name }} | MEX LMS Admin @endsection

    @section('content')
        <div class="row">
            <div class="col-12 d-flex justify-content-between align-items-center mb-3">
                <h1 class="m-0">{{ $instructor->first_name }} {{ $instructor->last_name }}</h1>
                <a href="{{ route('instructor.edit', $instructor->id) }}" class="btn btn-primary">
                    <i class="fa fa-edit"></i> Update Instructor
                </a>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3 align-items-start">
                    <div class="col-md-3">
                        @php
                            $photo = $instructor->image
                                ? asset('storage/'.$instructor->image)
                                : asset('storage/app-images/avatar.png');
                        @endphp
                        <img src="{{ $photo }}"
                             alt="{{ $instructor->first_name }} {{ $instructor->last_name }}"
                             class="img-fluid rounded shadow-sm">
                    </div>

                    <div class="col-md-9">
                        <p class="mb-1">
                            <strong>Organization:</strong>
                            {{-- Show relation name if you have it; fallback to string column or em dash --}}
                            {{ $instructor->organization->name ?? $instructor->organization ?? '—' }}
                        </p>
                        <p class="mb-1">
                            <strong>Status:</strong> {{ $instructor->is_active ? 'Active' : 'Inactive' }}
                        </p>
                        @if($instructor->email)
                            <p class="mb-1"><strong>Email:</strong> {{ $instructor->email }}</p>
                        @endif
                        @if($instructor->phone)
                            <p class="mb-1"><strong>Phone:</strong> {{ $instructor->phone }}</p>
                        @endif>

                        @if($instructor->bio)
                            <hr class="my-3">
                            <div class="preserve-lines">{!! nl2br(e($instructor->bio)) !!}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Lessons attached to this instructor -->
        <div class="card shadow mb-4">
            <div class="card-header">
                <h3 class="m-0">
                    Lessons ({{ $instructor->lessons->count() }})
                </h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle" id="dataTableLessons" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>Title</th>
                            <th>Series</th>
                            <th class="text-center">Videos</th>
                            <th class="text-center" style="width:120px;">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($instructor->lessons as $l)
                            @php
                                // Count DISTINCT video IDs from both sources (direct + via sections)
                                $directIds   = $l->videos->pluck('id');
                                $sectionIds  = $l->sections->flatMap->videos->pluck('id');
                                $videoCount  = $directIds->merge($sectionIds)->unique()->count();
                            @endphp
                            <tr>
                                <td>{{ $l->title }}</td>
                                <td>{{ $l->course->title ?? '' }}</td>
                                <td class="text-center">{{ $videoCount }}</td>
                                <td class="text-center">
                                    <a href="{{ route('lesson.view', $l->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No lessons attached.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- /Lessons -->
    @endsection
</x-admin-master>
