<x-admin-master>

    @section('page-title')
        Dashboard | {{$settings->app_name}} Admin
    @endsection

    @section('styles')
            <style>
                .embedded-footer.layout-align-end-end { display: none !important; }
            </style>
    @endsection

    @section('content')

        <h1 class="h3 m-3 text-gray-800">MEX Dashboard</h1>

        <div class="row text-center">
            <div class="col-sm-3">
                <a href="{{route('organization.index')}}" class="card">
                    <div class="card-header">
                        <h2 class="text-primary">Orgs</h2>
                    </div>
                    <div class="card-body">
                        <h2>{{ $organizations->count() }}</h2>
                    </div>
                </a>
            </div>
            <div class="col-sm-3">
                <a href="{{route('admin.courses.index')}}" class="card">
                    <div class="card-header">
                        <h2 class="text-primary">Courses</h2>
                    </div>
                    <div class="card-body">
                        <h2>{{ $course->count() }}</h2>
                    </div>
                </a>
            </div>
            <div class="col-sm-3">
                <a href="{{route('lesson.index')}}" class="card">
                    <div class="card-header">
                        <h2 class="text-primary">Lessons</h2>
                    </div>
                    <div class="card-body">
                        <h2>{{ $lessons->count() }}</h2>
                    </div>
                </a>
            </div>
            <div class="col-sm-3">
                <a href="{{route('student.index')}}" class="card">
                    <div class="card-header">
                        <h2 class="text-primary">Students</h2>
                    </div>
                    <div class="card-body">
                        <h2>{{ $student->count() }}</h2>
                    </div>
                </a>
            </div>
        </div>

        <div class="card mb-4 mt-4">
            <div class="card-header">
                <h3 class="mb-0">Site Analytics</h3>
            </div>
            <div class="card-body p-0">
                <iframe width="100%" height="443" src="https://lookerstudio.google.com/embed/reporting/258b8b70-2bcd-4276-88d7-2a635ddb53ca/page/3mrfF" frameborder="0" style="border:0" allowfullscreen sandbox="allow-storage-access-by-user-activation allow-scripts allow-same-origin allow-popups allow-popups-to-escape-sandbox"></iframe>
            </div>
        </div>


        @endsection

</x-admin-master>
