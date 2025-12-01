<x-admin-master>
    @section('page-title') Pages | MEX Learning Admin @endsection

    @section('favicon')
        {{asset('/storage/app-images/favicon.png')}}
    @endsection

    @section('content')
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 text-gray-800">MEX Pages</h1>
            <a href="{{ route('utilities.page_create') }}" class="btn btn-primary">Create Page</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                    <tr>
                        <th>Route Name</th>
                        <th>Slug</th>
                        <th>Title</th>
                        <th>H1</th>
                        <th>Active</th>
                        <th>Updated</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($pages as $page)
                        <tr>
                            <td><code>{{ $page->route_name }}</code></td>
                            <td>{{ $page->slug }}</td>
                            <td>{{ $page->title }}</td>
                            <td>{{ $page->h1 }}</td>
                            <td>{!! $page->is_active ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>' !!}</td>
                            <td>{{ $page->updated_at?->diffForHumans() }}</td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-secondary" href="{{ route('utilities.page_edit', $page) }}">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted">No pages yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endsection
</x-admin-master>
