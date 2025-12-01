<x-admin-master>

    @section('page-title')
        Instructors | MEX LMS Admin
    @endsection

    @section('favicon')
        {{asset('/storage/app-images/favicon.png')}}
    @endsection

    @section('styles')

    @endsection

    @section('content')
            <div class="row">
                <div class="col-sm-7">
                    <h1 class="m-3">Instructors</h1>
                </div>
                <div class="col-sm-5">
                    <a href="{{route('instructor.create')}}" class="btn btn-primary btn-right mt-3 mr-2">
                        <i class="fa fa-plus"></i> Add Instructor
                    </a>
                </div>
            </div>


            <!-- Instructors Table -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTableStudents" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th style="width:64px;">Photo</th>
                                <th>Name</th>
                                <th>Company</th>
                                <th>Lessons</th>
                                <th style="width:120px;"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($instructors as $instructor)
                                @php
                                    // Try common fields; fall back to a default avatar
                                    $img =
                                        (!empty($instructor->photo_url) ? $instructor->photo_url :
                                        (!empty($instructor->photo)     ? asset('storage/'.$instructor->photo) :
                                        (!empty($instructor->image)     ? asset('storage/'.$instructor->image) :
                                                                          asset('storage/app-images/avatar.png'))));
                                @endphp
                                <tr>
                                    <td class="text-center align-middle">
                                        <img src="{{ $img }}" alt=" {{ $instructor->first_name }} {{ $instructor->last_name }}"
                                             style="width:48px;height:48px;object-fit:cover;"
                                             class="rounded-circle">
                                    </td>

                                    <td class="align-middle">
                                        {{ $instructor->first_name }} {{ $instructor->last_name }}
                                    </td>

                                    <td class="align-middle">
                                        {{ $instructor->organization }}
                                    </td>

                                    <td class="align-middle">
                                        {{ $instructor->lessons_count }}

                                        {{-- Compact list of lesson links (show all) --}}
                                        @if($instructor->lessons_count > 0)
                                            <div class="mt-2 small">
                                                <ul class="mb-0 ps-3">
                                                    @foreach($instructor->lessons as $l)
                                                        <li>
                                                            <a href="{{ route('lesson.view', $l->id) }}">
                                                                {{ $l->title }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </td>

                                    <td class="align-middle">
                                        <div class="d-flex justify-content-center gap-4">
                                            <a href="{{ route('instructor.view', $instructor->id) }}" title="View">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{ route('instructor.edit', $instructor->id) }}" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <form action="{{ route('instructor.deactivate', $instructor->id) }}" method="post" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-link p-0 m-0 border-0 bg-transparent" title="Deactivate">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /Instructors Table -->



        @endsection

    @section('scripts')

    @endsection

</x-admin-master>
