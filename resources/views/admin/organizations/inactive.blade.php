<x-admin-master>

    @section('page-title')
        Inactive Organizations | MEX LMS Admin
    @endsection

    @section('favicon')
        {{asset('/storage/app-images/favicon.png')}}
    @endsection

    @section('styles')

    @endsection

    @section('content')
        <h1 class="m-3">Inactive Organizations</h1>


        <!-- Organizations Table -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTableOrganizations" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Lessons</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($organizations as $organization)
                            <tr>
                                <td></td>
                                <td>{{$organization->name}}</td>
                                <td>61</td>
                                <td>
                                    <div class="row">
                                        <div class="col-4">
                                            <a href="{{route('organization.view', $organization->id)}}">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </div>
                                        <div class="col-4">
                                            <a href="{{route('organization.edit', $organization->id)}}">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        </div>
                                        <div class="col-4">
                                            <form action="{{route('organization.activate', $organization->id)}}" method="post">
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
        <!-- /Organizations Table -->


    @endsection

    @section('scripts')

    @endsection

</x-admin-master>
