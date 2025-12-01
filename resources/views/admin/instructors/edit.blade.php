{{-- resources/views/admin/instructors/edit.blade.php --}}
<x-admin-master>
    @section('page-title')
        Update: {{ $instructor->first_name }} {{ $instructor->last_name }} | MEX LMS Admin
    @endsection

    @section('favicon')
        {{ asset('/storage/app-images/favicon.png') }}
    @endsection

    @section('content')
        <h1 class="m-3"><span>Update:</span> {{ $instructor->first_name }} {{ $instructor->last_name }}</h1>

        <div class="card shadow mb-4">
            @if ($errors->any())
                <div class="card-header">
                    <div class="alert alert-danger mb-0">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div class="card-header">
                    <div class="alert alert-success mb-0">{{ session('success') }}</div>
                </div>
            @endif

            <div class="card-body">
                <form action="{{ route('instructor.update', $instructor->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <div class="row">
                            <div class="col-6">
                                <label for="is_active">Active</label>
                                <select name="is_active" id="is_active" class="form-control">
                                    <option value="1" {{ old('is_active', (int)$instructor->is_active) === 1 ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ old('is_active', (int)$instructor->is_active) === 0 ? 'selected' : '' }}>No</option>
                                </select>
                            </div>

                            <div class="col-6"></div>

                            <div class="col-6">
                                <label for="first_name" class="mt-3">First Name <span class="text-danger">(Required)</span></label>
                                <input type="text" class="form-control" name="first_name"
                                       value="{{ old('first_name', $instructor->first_name) }}">
                            </div>

                            <div class="col-6">
                                <label for="last_name" class="mt-3">Last Name <span class="text-danger">(Required)</span></label>
                                <input type="text" class="form-control" name="last_name"
                                       value="{{ old('last_name', $instructor->last_name) }}">
                            </div>

                            <div class="col-12">
                                <label for="organization" class="mt-3">Organization</label>
                                <input type="text" class="form-control" name="organization"
                                       value="{{ old('organization', $instructor->organization) }}">
                            </div>

                            <div class="col-sm-6">
                                <label for="phone" class="mt-3">Phone Number</label>
                                <input type="text" class="form-control" name="phone"
                                       value="{{ old('phone', $instructor->phone) }}">
                            </div>

                            <div class="col-sm-6">
                                <label for="email" class="mt-3">Email <span class="text-danger">(Required)</span></label>
                                <input type="email" class="form-control" name="email"
                                       value="{{ old('email', $instructor->email) }}">
                            </div>

                            <div class="col-12">
                                <label for="bio" class="mt-3">Bio</label>
                                <textarea name="bio" id="bio" cols="30" rows="8" class="form-control">{{ old('bio', $instructor->bio) }}</textarea>
                            </div>

                            <div class="col-sm-6">
                                <label for="image" class="mt-3">Upload Image</label>
                                <input type="file" class="form-control" name="image" accept="image/*">
                                @php
                                    $img = $instructor->image ? asset('storage/'.$instructor->image) : asset('storage/app-images/avatar.png');
                                @endphp
                                <div class="mt-2">
                                    <img src="{{ $img }}" alt="Current photo" style="width:80px;height:80px;object-fit:cover;" class="rounded">
                                    <div class="small text-muted mt-1">Uploading a new image will replace the current one.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="col-sm-6">
                            <a href="{{ route('instructor.index') }}" class="btn btn-secondary width-100">Cancel</a>
                        </div>
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-primary width-100">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endsection

    @section('scripts')
        <script>
            // Simple live phone formatter (optional)
            document.addEventListener('DOMContentLoaded', function () {
                const phone = document.querySelector("input[name='phone']");
                if (!phone) return;
                phone.addEventListener('input', function () {
                    const digits = this.value.replace(/\D+/g, '');
                    if (digits.length === 10) {
                        this.value = digits.replace(/^(\d{3})(\d{3})(\d{4})$/, '$1-$2-$3');
                    }
                });
            });
        </script>
    @endsection
</x-admin-master>
