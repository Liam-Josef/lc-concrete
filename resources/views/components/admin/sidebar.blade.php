<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{route('home.index')}}">
        <div class="sidebar-brand-icon">
           <img src="{{ asset('storage/' . (($settings->logo ?? null) ?: 'app-images/ship-white.png')) }}" alt="Merchants Exchange Ship" class="mt-5" />
        </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0 mt-5">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="{{route('admin.index')}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        CRM
    </div>

    <!-- Nav Item - Organizations Collapse Menu -->
    <li class="nav-item {{ Request::is('mex-admin/organizations/*') ? 'active' : '' }}">
        <a class="nav-link {{ Request::is(['mex-admin/dashboard*', 'mex-admin/contacts*', 'mex-admin/lessons*', 'mex-admin/students*', 'mex-admin/news*', 'mex-admin/utilities*', 'mex-admin/instructors*', 'mex-admin/series*']) ? 'collapsed' : '' }}" href="#" data-toggle="collapse" data-target="#collapseOrganizations" aria-expanded="true" aria-controls="collapsePages">
            <i class="fas fa-fw fa-building"></i>
            <span>Organizations</span>
        </a>
        <div id="collapseOrganizations" class="collapse {{ Request::is('mex-admin/organizations*') ? 'show' : '' }}" aria-labelledby="headingOrganizations" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Organization Menu:</h6>
                <a class="collapse-item {{ Request::is('mex-admin/organizations/index*') ? 'active' : '' }}" href="{{route('organization.index')}}">View All</a>
                <a class="collapse-item {{ Request::is('mex-admin/organizations/create*') ? 'active' : '' }}" href="{{route('organization.create')}}">Add New</a>
                <a class="collapse-item {{ Request::is('mex-admin/organizations/inactive*') ? 'active' : '' }}" href="{{route('organization.inactive')}}">Inactive Orgs</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Students Collapse Menu -->
    <li class="nav-item {{ Request::is('mex-admin/students/*') ? 'active' : '' }}">
        <a class="nav-link {{ Request::is(['mex-admin/dashboard*', 'mex-admin/organizations*', 'mex-admin/contacts*', 'mex-admin/lessons*', 'mex-admin/students*', 'mex-admin/utilities*', 'mex-admin/instructors*', 'mex-admin/series*']) ? 'collapsed' : '' }}" href="#" data-toggle="collapse" data-target="#collapseStudents" aria-expanded="true" aria-controls="collapsePages">
            <i class="fas fa-fw fa-graduation-cap"></i>
            <span>Students</span>
        </a>
        <div id="collapseStudents" class="collapse {{ Request::is('mex-admin/students*') ? 'show' : '' }}" aria-labelledby="headingStudents" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Students Menu:</h6>
                <a class="collapse-item {{ Request::is('mex-admin/students/index*') ? 'active' : '' }}" href="{{route('student.index')}}">View All Students</a>
                <a class="collapse-item {{ Request::is('mex-admin/students/create*') ? 'active' : '' }}" href="{{route('student.create')}}">Add Students</a>
                <a class="collapse-item {{ Request::is('mex-admin/students/inactive*') ? 'active' : '' }}" href="{{route('student.inactive')}}">Inactive Students</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Contacts Collapse Menu -->
{{--    <li class="nav-item {{ Request::is('mex-admin/contacts/*') ? 'active' : '' }}">--}}
{{--        <a class="nav-link {{ Request::is(['mex-admin/dashboard*', 'mex-admin/organizations*', 'mex-admin/lessons*', 'mex-admin/students*', 'mex-admin/news*', 'mex-admin/utilities*', 'mex-admin/instructors*', 'mex-admin/series*']) ? 'collapsed' : '' }}" href="#" data-toggle="collapse" data-target="#collapseContacts" aria-expanded="true" aria-controls="collapsePages">--}}
{{--            <i class="fas fa-fw fa-users"></i>--}}
{{--            <span>Contacts</span>--}}
{{--        </a>--}}
{{--        <div id="collapseContacts" class="collapse {{ Request::is('mex-admin/contacts*') ? 'show' : '' }}" aria-labelledby="headingContacts" data-parent="#accordionSidebar">--}}
{{--            <div class="bg-white py-2 collapse-inner rounded">--}}
{{--                <h6 class="collapse-header">Contacts Menu:</h6>--}}
{{--                <a class="collapse-item {{ Request::is('mex-admin/contacts/index*') ? 'active' : '' }}" href="{{route('contact.index')}}">All Contacts</a>--}}
{{--                <a class="collapse-item {{ Request::is('mex-admin/contacts/create*') ? 'active' : '' }}" href="{{route('contact.create')}}">Add Contact</a>--}}
{{--                <a class="collapse-item {{ Request::is('mex-admin/contacts/inactive*') ? 'active' : '' }}" href="{{route('contact.inactive')}}">Inactive Contacts</a>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </li>--}}

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        LMS
    </div>

    <!-- Nav Item - Series Collapse Menu -->
    <li class="nav-item {{ Request::is('mex-admin/series/*') ? 'active' : '' }}">
        <a class="nav-link {{ Request::is(['mex-admin/dashboard*', 'mex-admin/organizations*', 'mex-admin/contacts*', 'mex-admin/students*', 'mex-admin/news*', 'mex-admin/utilities*', 'mex-admin/instructors*']) ? 'collapsed' : '' }}" href="#" data-toggle="collapse" data-target="#collapseCourses" aria-expanded="true" aria-controls="collapseCourses">
            <i class="fas fa-fw fa-sitemap"></i>
            <span>Course Series</span>
        </a>
        <div id="collapseCourses" class="collapse {{ Request::is('mex-admin/series*') ? 'show' : '' }}" aria-labelledby="headingCourses" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Course Menu:</h6>
                <a class="collapse-item {{ Request::is('mex-admin/series*') ? 'active' : '' }}" href="{{route('admin.courses.index')}}">All Series</a>
                <a class="collapse-item {{ Request::is('mex-admin/series/create*') ? 'active' : '' }}" href="{{route('admin.courses.create')}}">Add a Series</a>
{{--                <a class="collapse-item {{ Request::is('mex-admin/lessons/inactive*') ? 'active' : '' }}" href="{{route('lesson.inactive')}}">Inactive Courses</a>--}}
            </div>
        </div>
    </li>

    <!-- Nav Item - Courses Collapse Menu -->
    <li class="nav-item {{ Request::is('mex-admin/course/*') ? 'active' : '' }}">
        <a class="nav-link {{ Request::is(['mex-admin/dashboard*', 'mex-admin/organizations*', 'mex-admin/contacts*', 'mex-admin/students*', 'mex-admin/news*', 'mex-admin/utilities*', 'mex-admin/instructors*', 'mex-admin/series*']) ? 'collapsed' : '' }}" href="#" data-toggle="collapse" data-target="#collapseLessons" aria-expanded="true" aria-controls="collapsePages">
            <i class="fas fa-fw fa-chalkboard"></i>
            <span>Courses</span>
        </a>
        <div id="collapseLessons" class="collapse {{ Request::is('mex-admin/course*') ? 'show' : '' }}" aria-labelledby="headingLessons" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Course Menu:</h6>
                <a class="collapse-item {{ Request::is('mex-admin/course/index*') ? 'active' : '' }}" href="{{route('lesson.index')}}">All Courses</a>
                <a class="collapse-item {{ Request::is('mex-admin/course/create*') ? 'active' : '' }}" href="{{route('lesson.create')}}">Add a Course</a>
                <a class="collapse-item {{ Request::is('mex-admin/course/inactive*') ? 'active' : '' }}" href="{{route('lesson.inactive')}}">Inactive Courses</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Organizations Collapse Menu -->
    <li class="nav-item {{ Request::is('mex-admin/organizations/*') ? 'active' : '' }}">
        <a class="nav-link {{ Request::is(['mex-admin/dashboard*', 'mex-admin/contacts*', 'mex-admin/lessons*', 'mex-admin/students*', 'mex-admin/news*', 'mex-admin/utilities*', 'mex-admin/organizations*', 'mex-admin/series*']) ? 'collapsed' : '' }}" href="#" data-toggle="collapse" data-target="#collapseInstructors" aria-expanded="true" aria-controls="collapsePages">
            <i class="fas fa-fw fa-chalkboard-teacher"></i>
            <span>Instructors</span>
        </a>
        <div id="collapseInstructors" class="collapse {{ Request::is('mex-admin/instructors*') ? 'show' : '' }}" aria-labelledby="headingInstructors" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Instructors Menu:</h6>
                <a class="collapse-item {{ Request::is('mex-admin/instructors*') ? 'active' : '' }}" href="{{route('instructor.index')}}">View All</a>
                <a class="collapse-item {{ Request::is('mex-admin/instructors/create*') ? 'active' : '' }}" href="{{route('instructor.create')}}">Add New</a>
{{--                <a class="collapse-item {{ Request::is('mex-admin/instructors/inactive*') ? 'active' : '' }}" href="{{route('instructor.inactive')}}">Inactive Instructors</a>--}}
            </div>
        </div>
    </li>


    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        System
    </div>

    <!-- Heading -->
{{--    <div class="sidebar-heading">--}}
{{--        Interface--}}
{{--    </div>--}}

    <!-- Nav Item - News Collapse Menu -->
{{--    <li class="nav-item {{ Request::is('mex-admin/news/*') ? 'active' : '' }}">--}}
{{--        <a class="nav-link {{ Request::is(['mex-admin/dashboard*', 'mex-admin/organizations*', 'mex-admin/contacts*', 'mex-admin/lessons*', 'mex-admin/students*', 'mex-admin/utilities*', 'mex-admin/instructors*', 'mex-admin/series/*']) ? 'collapsed' : '' }}" href="#" data-toggle="collapse" data-target="#collapseNews" aria-expanded="true" aria-controls="collapseTwo">--}}
{{--            <i class="fas fa-fw fa-cog"></i>--}}
{{--            <span>News</span>--}}
{{--        </a>--}}
{{--        <div id="collapseNews" class="collapse {{ Request::is('mex-admin/news*') ? 'show' : '' }}" aria-labelledby="headingNews" data-parent="#accordionSidebar">--}}
{{--            <div class="bg-white py-2 collapse-inner rounded">--}}
{{--                <h6 class="collapse-header">News Menu:</h6>--}}
{{--                <a class="collapse-item" href="{{route('news.create')}}">Create Post</a>--}}
{{--                <a class="collapse-item" href="cards.html">Cards</a>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </li>--}}


    <!-- Nav Item - Accounting Collapse Menu -->
    <li class="nav-item {{ Request::is('mex-admin/accounting/*') ? 'active' : '' }}">
        <a class="nav-link {{ Request::is(['mex-admin/dashboard*', 'mex-admin/organizations*', 'mex-admin/contacts*', 'mex-admin/lessons*', 'mex-admin/students*', 'mex-admin/news*', 'mex-admin/instructors*', 'mex-admin/series*', 'mex-admin/utilities*']) ? 'collapsed' : '' }}" href="#" data-toggle="collapse" data-target="#collapseAccounting" aria-expanded="true" aria-controls="collapseAccounting">
            <i class="fas fa-fw fa-receipt"></i>
            <span>Accounting</span>
        </a>
        <div id="collapseAccounting" class="collapse {{ Request::is('mex-admin/accounting*') ? 'show' : '' }}" aria-labelledby="headingAccounting" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Reconciliation:</h6>
                <a class="collapse-item {{ Request::is('mex-admin/accounting*') ? 'active' : '' }}" href="{{route('admin.transactions.index')}}">All Transactions</a>
{{--                <a class="collapse-item {{ Request::is('mex-admin/accounting/unsettled-transactions*') ? 'active' : '' }}" href="{{route('admin.transactions.unsettled')}}">Unsettled Transactions</a>--}}
            </div>
            {{--            <div class="bg-white py-2 collapse-inner rounded">--}}
            {{--                <h6 class="collapse-header">Page Menu:</h6>--}}
            {{--                <a class="collapse-item" href="utilities-color.html">Pages</a>--}}
            {{--            </div>--}}
        </div>
    </li>

    <!-- Nav Item - Utilities Collapse Menu -->
    <li class="nav-item {{ Request::is('mex-admin/utilities/*') ? 'active' : '' }}">
        <a class="nav-link {{ Request::is(['mex-admin/dashboard*', 'mex-admin/organizations*', 'mex-admin/contacts*', 'mex-admin/lessons*', 'mex-admin/students*', 'mex-admin/news*', 'mex-admin/instructors*', 'mex-admin/series*']) ? 'collapsed' : '' }}" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-fw fa-wrench"></i>
            <span>Utilities</span>
        </a>
        <div id="collapseUtilities" class="collapse {{ Request::is('mex-admin/utilities*') ? 'show' : '' }}" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">User Menu:</h6>
                <a class="collapse-item {{ Request::is('mex-admin/utilities/users*') ? 'active' : '' }}" href="{{route('admin.utilities.user.index')}}">All Users</a>
                <a class="collapse-item {{ Request::is('mex-admin/utilities/users/create*') ? 'active' : '' }}" href="{{route('admin.utilities.user.create')}}">Add a User</a>

                <h6 class="collapse-header">Dev Menu:</h6>
                <a class="collapse-item {{ Request::is('mex-admin/utilities/app*') ? 'active' : '' }}" href="{{route('admin.app.edit')}}">App Info</a>
{{--                <a class="collapse-item" href="utilities-color.html">Page Keywords</a>--}}
            </div>
        </div>
    </li>


    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
