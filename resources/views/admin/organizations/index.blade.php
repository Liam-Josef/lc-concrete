<x-admin-master>

    @section('page-title')
        Organizations | MEX LMS Admin
    @endsection

    @section('favicon')
        {{asset('/storage/app-images/favicon.png')}}
    @endsection

    @section('styles')

    @endsection

    @section('content')
        <div class="row">
            <div class="col-sm-8">
                <h1 class="m-3">Organizations</h1>
            </div>
            <div class="col-sm-4">
                <a href="{{route('organization.create')}}" class="btn btn-primary btn-right mt-3 mr-2">
                    <i class="fa fa-plus"></i> Add Org
                </a>
            </div>
        </div>


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
{{--                                    <td>{{ $organizations->lessons->count() }}</td>--}}
                                    <td>4</td>
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
                                                <form action="{{route('organization.deactivate', $organization->id)}}" method="post">
                                                    @csrf
                                                    @method('PUT')

                                                    <button type="submit" class="btn btn-link p-0 m-0 border-0 bg-transparent"><i class="fa fa-trash"></i></button>
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
