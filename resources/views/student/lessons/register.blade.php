<x-home-fullscreen-index>

    @section('page-title')
        Register: {{$lesson->title}} | {{$settings->app_name}}
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

    @section('content')

            <div class="white-back mt-5">
                <h1 class="my-4">Register for {{$lesson->title}}</h1>

                <div class="row col-sm-12">
                    @if(session('success'))
                        <div class="alert alert-success mt-3">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger mt-3">{{ session('error') }}</div>
                    @endif

                </div>

                <div class="row mb-5">
                    <div class="col-sm-6">
                        <img src="{{ asset('storage/' . $lesson->image) }}" class="img-responsive" alt="{{$lesson->title}}" title="{{$lesson->title}}"/>
                    </div>
                    <div class="col-sm-6">
                        <h4><b>Total Hours:</b> {{$lesson->total_hours}} hours</h4>
                        <h4><b>CEU Available:</b> {{$lesson->total_ceu}}</h4>
                        <h4><b>Lesson Cost:</b> ${{$lesson->student_cost}}</h4>
                        @php
                            $isRegistered = auth()->check() && auth()->user()->student?->lessons->contains($lesson->id);
                        @endphp

                        @php
                            $user        = auth()->user();
                            $hasStudent  = (bool) ($user?->student);
                            $isAdmin     = (bool) ($user?->is_admin);
                        @endphp

                        @if ($isRegistered)
                            <a class="btn btn-outline-secondary mt-5" href="{{ route('lessons.start', $lesson) }}">
                                Start Lesson
                            </a>
                        @else
                            {{-- If admin but not yet a student, show quick-create Student button --}}
                            @if ($isAdmin && !$hasStudent)
                                <form method="POST" action="{{ route('student.become') }}" class="d-inline">
                                    @csrf
                                    <button class="btn btn-primary mt-3">
                                        Register as Student
                                    </button>
                                </form>
                            @else

                            {{-- Main register button (disabled until a Student row exists) --}}
                            <button class="btn btn-primary mt-5"
                                    type="button"
                                    data-bs-toggle="modal"
                                    data-bs-target="#payRegisterModal"
                                    {{ $hasStudent ? '' : 'disabled' }}
                                    title="{{ $hasStudent ? '' : 'Create a student profile first' }}">
                                Register for Course
                            </button>
                            @endif
                        @endif


                        <div class="modal fade" id="payRegisterModal" tabindex="-1" aria-labelledby="payRegisterModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form id="payRegisterForm" action="{{ route('lesson.student-register', $lesson->id) }}" method="POST" autocomplete="off" novalidate>
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="payRegisterModalLabel">Pay & Register</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>

                                        <div class="modal-body">
                                            <p class="text-muted mb-3">
                                                You’re registering for <strong>{{ $lesson->title }}</strong> — <strong>${{ $lesson->student_cost }}</strong>.
                                            </p>

                                            @php $TAKE_PAYMENT = false; @endphp
                                            <div class="mb-3">
                                                <label class="form-label">Name on card</label>
                                                <input
                                                    id="card_name"
                                                    class="form-control"
                                                    name="{{ $TAKE_PAYMENT ? 'card_name' : '__demo_card_name' }}"
                                                    value="{{ auth()->user()->name ?? '' }}"
                                                    autocomplete="cc-name"
                                                    required
                                                >
                                                <div class="invalid-feedback">Please enter the name on the card.</div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Card number</label>
                                                <input
                                                    id="card_number"
                                                    class="form-control"
                                                    name="{{ $TAKE_PAYMENT ? 'card_number' : '__demo_card_number' }}"
                                                    inputmode="numeric"
                                                    autocomplete="cc-number"
                                                    placeholder="4242 4242 4242 4242"
                                                >
                                                <div class="invalid-feedback">Enter a valid card number.</div>
                                            </div>

                                            <div class="row g-3">
                                                <div class="col-6">
                                                    <label class="form-label">Expiry (MM/YY)</label>
                                                    <input
                                                        id="card_exp"
                                                        class="form-control"
                                                        name="{{ $TAKE_PAYMENT ? 'card_exp' : '__demo_card_exp' }}"
                                                        inputmode="numeric"
                                                        autocomplete="cc-exp"
                                                        placeholder="MM/YY"
                                                    >
                                                    <div class="invalid-feedback">Format must be MM/YY.</div>
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label">CVC</label>
                                                    <input
                                                        id="card_cvc"
                                                        class="form-control"
                                                        name="{{ $TAKE_PAYMENT ? 'card_cvc' : '__demo_card_cvc' }}"
                                                        inputmode="numeric"
                                                        autocomplete="cc-csc"
                                                        placeholder="CVC"
                                                    >
                                                    <div class="invalid-feedback">3–4 digits.</div>
                                                </div>
                                            </div>

                                            <div class="mt-3">
                                                <label class="form-label">Billing ZIP/Postal</label>
                                                <input
                                                    id="billing_zip"
                                                    class="form-control"
                                                    name="{{ $TAKE_PAYMENT ? 'billing_zip' : '__demo_billing_zip' }}"
                                                    inputmode="numeric"
                                                    autocomplete="postal-code"
                                                    placeholder="ZIP/Postal code"
                                                >
                                                <div class="invalid-feedback">Please enter your billing ZIP/postal code.</div>
                                            </div>

                                            <small class="text-muted d-block mt-3">
                                                Note: This is a placeholder form. This modal will simply continue just by pressing "Pay & Register"
                                            </small>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button id="payRegisterSubmit" type="submit" class="btn btn-primary" {{ $isRegistered ? 'disabled' : '' }}>
                                                {{ $isRegistered ? 'Already Registered' : 'Pay & Register' }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


    @endsection

    @section('scripts')
        <script>
            (function () {
                const form = document.getElementById('payRegisterForm');
                if (!form) return;

                // Only ENFORCE validation when you're actually charging cards
                const ENFORCE = {{ $TAKE_PAYMENT ? 'true' : 'false' }};

                const el = {
                    number: document.getElementById('card_number'),
                    exp:    document.getElementById('card_exp'),
                    cvc:    document.getElementById('card_cvc'),
                    name:   document.getElementById('card_name'),
                    zip:    document.getElementById('billing_zip'),
                };

                if (el.number) el.number.addEventListener('input', function () {
                    this.value = this.value.replace(/[^\d]/g,'').replace(/(.{4})/g,'$1 ').trim();
                    this.classList.remove('is-invalid');
                });
                if (el.exp) el.exp.addEventListener('input', function () {
                    let v = this.value.replace(/[^\d]/g,'').slice(0,4);
                    if (v.length >= 3) v = v.slice(0,2) + '/' + v.slice(2);
                    this.value = v;
                    this.classList.remove('is-invalid');
                });
                [el.cvc, el.name, el.zip].forEach(i => i && i.addEventListener('input', () => i.classList.remove('is-invalid')));

                form.addEventListener('submit', function (e) {
                    if (!ENFORCE) return;

                    const num = (el.number?.value || '').replace(/\s+/g,'');
                    if (!/^\d{12,19}$/.test(num) || !luhn(num)) return stop(e, el.number, 'Enter a valid card number.');
                    if (!/^(0[1-9]|1[0-2])\/\d{2}$/.test(el.exp?.value || '')) return stop(e, el.exp, 'Format must be MM/YY.');
                    if (!/^\d{3,4}$/.test(el.cvc?.value || '')) return stop(e, el.cvc, '3–4 digits.');
                });

                function stop(e, input, msg){
                    e.preventDefault();
                    if (!input) return;
                    input.classList.add('is-invalid');
                    const fb = input.closest('.mb-3, .col-6')?.querySelector('.invalid-feedback');
                    if (fb) fb.textContent = msg;
                    input.focus();
                }
                function luhn(num){
                    let sum = 0, dbl = false;
                    for (let i = num.length - 1; i >= 0; i--) {
                        let d = +num[i];
                        if (dbl) { d *= 2; if (d > 9) d -= 9; }
                        sum += d; dbl = !dbl;
                    }
                    return sum % 10 === 0;
                }
            })();
        </script>
    @endsection


</x-home-fullscreen-index>
