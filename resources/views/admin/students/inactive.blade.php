<x-admin-master>

    @section('page-title')
        Inactive Students | MEX LMS Admin
    @endsection

    @section('favicon')
        {{asset('/storage/app-images/favicon.png')}}
    @endsection

    @section('styles')

    @endsection

    @section('content')
        <h1 class="m-3">Inactive Students</h1>


        <!-- Contacts Table -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTableStudents" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Lessons</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($students as $student)
                            <tr>
                                <td></td>
                                <td>{{$student->first_name}} {{$student->last_name}}</td>
                                <td>61</td>
                                <td>
                                    <div class="row">
                                        <div class="col-4">
                                            <a href="{{route('student.view', $student->id)}}">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </div>
                                        <div class="col-4">
                                            <a href="{{route('student.edit', $student->id)}}">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        </div>
                                        <div class="col-4">
                                            <form action="{{route('student.activate', $student->id)}}" method="post">
                                                @csrf
                                                @method('PUT')

                                                <button type="submit" class="btn btn-link p-0 m-0 border-0 bg-transparent"><i class="fa fa-check"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- /Contacts Table -->


    @endsection

    @section('scripts')

    @endsection

</x-admin-master>
