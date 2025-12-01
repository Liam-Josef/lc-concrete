<x-home-fullscreen-master>

    @section('page-title')
        My Account | {{$settings->app_name}}
    @endsection

    @section('description')
        Merchants Exchange Learning Management System
    @endsection

    @section('background-image')
        {{ asset('storage/' . (($settings->internal_background ?? null) ?: 'app-images/interior-banner-1.jpg')) }}
    @endsection

    @section('banner')
        <img src="{{asset('storage/app-images/interior-banner.jpg')}}" class="img-responsive" alt="MEX Learning Banner" title="MEX Learning Banner"/>
    @endsection

    @section('style')
        <style>
            footer {
                position: absolute;
                width: 100%;
                bottom: 0;
            }
        </style>
    @endsection

    @section('content')

            <div class="white-back">
                <h1 class="text-primary">My Account
                    <span>
                  @php
                      $u = auth()->user();

                      // Determine label
                      if ($u->is_admin) {
                          $label = 'Admin';
                      } elseif (method_exists($u, 'hasRole') && $u->hasRole('instructor')) {
                          $label = 'Instructor';
                      } elseif (method_exists($u, 'hasRole') && $u->hasRole('student')) {
                          $label = 'Student';
                      } elseif (!empty($u->role)) {
                          // fallback if you still keep a single 'role' column
                          $label = ucfirst($u->role);
                      } else {
                          // last resort: check relation without the helper
                          $label = ($u->relationLoaded('roles') ? $u->roles : $u->roles())
                              ->where('slug', 'instructor')->exists() ? 'Instructor'
                              : ( ($u->relationLoaded('roles') ? $u->roles : $u->roles())
                                    ->where('slug', 'student')->exists() ? 'Student' : 'Member');
                      }

                      // Map label -> badge class
                      $badge = match ($label) {
                          'Admin'      => 'badge-admin',
                          'Instructor' => 'badge-info',
                          'Student'    => 'badge-success',
                          default      => 'badge-secondary',
                      };
                  @endphp

                  <span class="badge {{ [
                        'Admin' => 'badge-admin',
                        'Instructor' => 'badge-info',
                        'Student' => 'badge-success',
                    ][$u->primary_role_label] ?? 'badge-secondary' }}">
                      {{ $u->primary_role_label }}
                    </span>

                </span>

                </h1>

                <div class="white-back">
                    <div class="row">
                        <div class="col-12">
                            <!-- Tabs -->
                            <ul class="nav nav-tabs" id="accountTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active"
                                       id="info-tab"
                                       data-bs-toggle="tab"
                                       href="#tab-info"
                                       role="tab"
                                       aria-controls="tab-info"
                                       aria-selected="true">Info</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link"
                                       id="billing-tab"
                                       data-bs-toggle="tab"
                                       href="#tab-billing"
                                       role="tab"
                                       aria-controls="tab-billing"
                                       aria-selected="false">Billing</a>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content border-left border-right border-bottom p-3" id="accountTabsContent">
                                <div class="tab-pane fade show active" id="tab-info" role="tabpanel" aria-labelledby="info-tab">
                                    <h5 class="mb-3">Basic Info</h5>

                                    <form method="POST" action="{{ route('user.info.update', $user->id) }}">
                                        @csrf
                                        @method('PUT')

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="name">Name</label>
                                                <input type="text" id="name" name="name"
                                                       class="form-control @error('name') is-invalid @enderror"
                                                       value="{{ old('name', auth()->user()->name) }}" required>
                                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label for="email">Email</label>
                                                <input type="email" id="email" name="email"
                                                       class="form-control @error('email') is-invalid @enderror"
                                                       value="{{ old('email', auth()->user()->email) }}" required>
                                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                            </div>
                                        </div>

                                        <button class="btn btn-primary">Save Changes</button>
                                    </form>
                                </div>

                                {{-- BILLING --}}
                                <div class="tab-pane fade" id="tab-billing" role="tabpanel" aria-labelledby="billing-tab">
                                    <h5 class="mb-3">Billing</h5>

                                    {{-- Payment methods --}}
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h6 class="card-title mb-2">Payment Methods</h6>
                                            <p class="mb-0 text-muted">Add or manage your saved cards here.</p>
                                            {{--                                    <a href="{{ route('billing.methods') }}" class="btn btn-sm btn-outline-primary mt-2">Manage</a>--}}
                                            <a href="#" class="btn btn-sm btn-outline-primary mt-2">Manage</a>
                                        </div>
                                    </div>

                                    {{-- Invoices --}}
                                    <div class="table-responsive">
                                        <table class="table table-striped table-sm mb-0">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Date</th>
                                                <th>Total</th>
                                                <th>Status</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @forelse($invoices ?? [] as $inv)
                                                <tr>
                                                    <td>{{ $inv->number }}</td>
                                                    <td>{{ $inv->date->format('M d, Y') }}</td>
                                                    <td>${{ number_format($inv->total, 2) }}</td>
                                                    <td><span class="badge badge-{{ $inv->paid ? 'success' : 'warning' }}">{{ $inv->paid ? 'Paid' : 'Due' }}</span></td>
                                                    <td class="text-right">
                                                        <a href="{{ route('billing.invoice.show', $inv->id) }}"
                                                           class="btn btn-sm btn-outline-secondary"
                                                           target="_blank">
                                                            View
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="5" class="text-muted">No invoices yet.</td></tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


        @endsection

        @section('scripts')
            <script src="{{asset('js/admin.js')}}"></script>
            <script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
            <script src="{{asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
            <script src="{{asset('js/datatables.js')}}"></script>
        @endsection


</x-home-fullscreen-master>
