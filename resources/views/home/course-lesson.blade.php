<x-home-fullscreen-index>

    @section('page-title')
       {{$lesson->title}} | {{$settings->app_name}}
    @endsection

    @section('description')
        {{$lesson->title}} | {{$settings->app_name}}
    @endsection

    @section('background-image')
        {{ asset('storage/' . (($settings->internal_background ?? null) ?: 'app-images/interior-banner-1.jpg')) }}
    @endsection

    @section('banner')
        <img src="{{asset('storage/app-images/interior-banner.jpg')}}" class="img-responsive" alt="MEX Learning Banner" title="MEX Learning Banner"/>
    @endsection

    @section('content')

            @php
                // User / student context (works for guests too)
                $user       = auth()->user();
                $student    = $user?->student;
                $hasStudent = (bool) $student;

                // Is this student already registered for this lesson?
                $isRegistered = false;
                if ($student) {
                    $isRegistered = $student->lessons()
                        ->where('lessons.id', $lesson->id)
                        ->exists();
                }

                /** toggle this to true once you actually charge cards via Stripe */
                $TAKE_PAYMENT = false;

                // Safely compute address flag only if user exists
                $hasAddress = $user
                    && !empty($user->address)
                    && !empty($user->city)
                    && !empty($user->state)
                    && !empty($user->zip);

                // Money bits
                $courseCost  = (float) ($lesson->student_cost ?? 0);
                $feesPercent = (float) ($settings->course_fee ?? 0);   // e.g. 0.03
                $fees        = $courseCost * $feesPercent;
                $cardTotal   = $courseCost + $fees;
            @endphp


         <!-- Title -->
        <div class="white-back mt-4">
            <div class="row">
                <div class="col-sm-8">
                    <h1 class="text-primary mb-0">{{$lesson->title}}</h1>
                </div>
                <div class="col-sm-4">
                    @auth
                        @if ($isRegistered)
                            <a href="{{ route('lessons.start', $lesson) }}" class="btn btn-primary btn-right mt-1">
                                Start Course
                            </a>
                        @else
                            <button class="btn btn-primary btn-right mt-1"
                                    type="button"
                                    data-bs-toggle="modal"
                                    data-bs-target="#payRegisterModal"
                                    {{ $hasStudent ? '' : 'disabled' }}
                                    title="{{ $hasStudent ? '' : 'Create a student profile first' }}">
                                Register for Course
                            </button>
                        @endif
                    @else
                        <button type="button" class="btn btn-primary btn-right mt-1" data-bs-toggle="modal" data-bs-target="#loginModal">
                            Login to Register
                        </button>
                    @endauth

                </div>
            </div>
        </div>
        <!-- /Title -->

        <!-- Lesson Info -->
            <div class="white-back">
                <div class="row">
                    <div class="col-sm-6">
                        <img src="{{ asset('storage/' . $lesson->image) }}" class="img-responsive" alt="{{$lesson->title}}" title="{{$lesson->title}}"/>
                    </div>
                    <div class="col-sm-6">
                        {{$lesson->long_description}}

                        <br/><br/>

                        <div class="row">

                            <div class="col-6"><h5 class="mb-1">Course Cost</h5></div>
                            <div class="col-6"><p class="mb-1"><b>${{ $lesson->student_cost }}</b></p></div>

                            <div class="col-6"><h5 class="mb-1">CEU's</h5></div>
                            <div class="col-6"><p class="mb-1">{{ $lesson->total_ceu }}</p></div>

                            <div class="col-6"><h5 class="mb-1">Course Duration</h5></div>
                            <div class="col-6">
                                <p class="mb-1">{{ $durationHours }} {{ \Illuminate\Support\Str::plural('hour', $durationHours) }}</p>
                            </div>


{{--                            <div class="col-6"><h5 class="mb-1">Sections</h5></div>--}}
{{--                            <div class="col-6"><p class="mb-1">{{ $sectionCount }} {{ \Illuminate\Support\Str::plural('Section', $sectionCount) }}</p></div>--}}

{{--                            <div class="col-6"><h5 class="mb-1">Videos</h5></div>--}}
{{--                            <div class="col-6"><p class="mb-1">{{ $videoCount }} {{ \Illuminate\Support\Str::plural('Video', $videoCount) }}</p></div>--}}
                        </div>

                    </div>
                </div>
            </div>

            <div class="white-back">
                <div class="row">
                    <div class="col-12">
                        <!-- Tabs -->
                        <ul class="nav nav-tabs justify-content-center" id="courseTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active"
                                        id="tab-one"
                                        data-bs-toggle="tab"
                                        data-bs-target="#pane-one"
                                        type="button" role="tab"
                                        aria-controls="pane-one"
                                        aria-selected="true">
                                    Learning Outcomes
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link"
                                        id="tab-two"
                                        data-bs-toggle="tab"
                                        data-bs-target="#pane-two"
                                        type="button" role="tab"
                                        aria-controls="pane-two"
                                        aria-selected="false">
                                    Course Notes
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link"
                                        id="tab-three"
                                        data-bs-toggle="tab"
                                        data-bs-target="#pane-three"
                                        type="button" role="tab"
                                        aria-controls="pane-three"
                                        aria-selected="false">
                                    Completion Requirements
                                </button>
                            </li>
                            {{--                                <li class="nav-item" role="presentation">--}}
                            {{--                                    <button class="nav-link disabled"--}}
                            {{--                                            id="tab-disabled"--}}
                            {{--                                            type="button" role="tab"--}}
                            {{--                                            aria-disabled="true">--}}
                            {{--                                        Disabled--}}
                            {{--                                    </button>--}}
                            {{--                                </li>--}}
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content pt-3" id="courseTabsContent">
                            <div class="tab-pane fade show active"
                                 id="pane-one"
                                 role="tabpanel"
                                 aria-labelledby="tab-one"
                                 tabindex="0">
                                <div class="preserve-lines">
                                    {{ $lesson->learning_outcomes }}
                                </div>
                            </div>

                            <div class="tab-pane fade"
                                 id="pane-two"
                                 role="tabpanel"
                                 aria-labelledby="tab-two"
                                 tabindex="0">
                                <div class="preserve-lines">
                                    {{ $lesson->course_notes }}
                                </div>
                            </div>

                            <div class="tab-pane fade"
                                 id="pane-three"
                                 role="tabpanel"
                                 aria-labelledby="tab-three"
                                 tabindex="0">
                                <div class="preserve-lines">
                                    {{ $lesson->completion_requirements }}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- /Lesson Info -->

            <!-- Related Lessons -->
            <div class="white-back">
                <div class="row">
                    <h3 class="text-primary">Related Courses</h3>

                    @forelse($lessons as $rel)
                        @php
                            // Prefer lesson image if you still have it; otherwise use the course image
                            $imgPath = $rel->image ?: optional($rel->course)->image;
                        @endphp

                        <div class="col-sm-4">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h3 class="h6 mb-0">{{ $rel->title }}</h3>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    @if($imgPath)
                                        <img
                                            src="{{ Storage::url($imgPath) }}"
                                            class="img-responsive mb-3"
                                            alt="{{ $rel->title }}"
                                            title="{{ $rel->title }}"
                                        />
                                    @endif

                                    <p class="flex-grow-1">{{ $rel->short_description }}</p>

                                    <a href="{{ route('home.course_lesson', $rel->id) }}"
                                       class="btn btn-primary btn-100 mt-auto">
                                        Learn More
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-muted">No related lessons yet.</div>
                    @endforelse
                </div>
            </div>
            <!-- /Related Lessons -->


            <!-- Login Modal -->
            <div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form id="modalLoginForm" method="POST" action="{{ route('ajax.login') }}" data-recaptcha="v3" data-recaptcha-action="login" data-ajax="true">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Log in to Continue</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <div id="loginErrors" class="alert alert-danger d-none"></div>
                                <div class="form-errors d-none"></div>

                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input name="email" type="email" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input name="password" type="password" class="form-control" required>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="remember" id="rememberMe" value="1">
                                            <label class="form-check-label" for="rememberMe">Remember me</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        @if (Route::has('register'))
                                            <div class="text-right">
                                                <a class="small text-right mt-1" href="{{ route('student.register') }}">Create an Account</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Log in</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <!-- /Login Modal -->

        <!-- Pay Modal -->
            <div class="modal fade" id="payRegisterModal" tabindex="-1" aria-labelledby="payRegisterModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        @php
                            /** toggle this to true once you actually charge cards via Stripe */
                            $TAKE_PAYMENT = false;

                            $user    = auth()->user();
                            $student = $user?->student;

                            // Check address on the STUDENT record
                            $hasAddress = $student
                                && !empty($student->address_line)
                                && !empty($student->city)
                                && !empty($student->state)
                                && !empty($student->zip);

                            // Money bits
                            $courseCost = (float) ($lesson->student_cost ?? 0);

                            // 👉 Pull the processing fee from apps->course_fee
                            $feesPercent = (float) ($settings->course_fee ?? 0);
                            $fees        = $courseCost * $feesPercent;
                            $cardTotal   = $courseCost + $fees;
                        @endphp


                        <form id="payRegisterForm"
                              action="{{ route('lesson.student-register', $lesson->id) }}"
                              method="POST"
                              novalidate>
                            @csrf

                            {{-- Payment method: "card" or "invoice" --}}
                            <input type="hidden" name="payment_method" id="payment_method" value="card">

                            <div class="modal-header">
                                {{-- 👇 Initially just says "Register" --}}
                                <h5 class="modal-title" id="payRegisterModalLabel">Register</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <p class="text-muted mb-3">
                                    You’re registering for
                                    <strong>{{ $lesson->title }}</strong>
                                    — Course cost:
                                    <strong>${{ number_format($courseCost, 2) }}</strong>.
                                </p>

                                {{-- Address section: ONLY show if student does NOT have an address on file --}}
                                @if(!$hasAddress)
                                    <div id="addressSection" class="mb-3">
                                        <h6>Mailing Address</h6>
                                        <p class="text-muted mb-2">
                                            Please complete your mailing address before proceeding with registration.
                                        </p>

                                        <div class="mb-2">
                                            <label class="form-label">Street Address</label>
                                            <input type="text"
                                                   class="form-control"
                                                   name="address"
                                                   id="addr_address"
                                                   value="{{ old('address', $student->address_line ?? '') }}"
                                                   required>
                                        </div>

                                        <div class="row g-2">
                                            <div class="col-md-5">
                                                <label class="form-label">City</label>
                                                <input type="text"
                                                       class="form-control"
                                                       name="city"
                                                       id="addr_city"
                                                       value="{{ old('city', $student->city ?? '') }}"
                                                       required>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">State</label>
                                                <select class="form-control"
                                                        name="state"
                                                        id="addr_state"
                                                        required>
                                                    <option value="">Select</option>
                                                    @foreach($states as $state)
                                                        <option value="{{ $state->code }}"
                                                            {{ old('state', $student->state ?? '') === $state->code ? 'selected' : '' }}>
                                                            {{ $state->state }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">ZIP/Postal</label>
                                                <input type="text"
                                                       class="form-control"
                                                       name="zip"
                                                       id="addr_zip"
                                                       value="{{ old('zip', $student->zip ?? '') }}"
                                                       required>
                                            </div>
                                        </div>

                                        <small id="addressNotice" class="text-muted d-block mt-3">
                                            <b>Next:</b> Choose a payment method.
                                        </small>
                                    </div>
                                @else
                                    {{-- They already have an address on file – just show it read-only --}}
                                    <div class="mb-3">
                                        <h6>Mailing Address</h6>
                                        <p class="mb-1">
                                            {{ $student->address_line }}<br>
                                            {{ $student->city }}, {{ $student->state }} {{ $student->zip }}
                                        </p>
                                        <small class="text-muted">
                                            If this looks wrong, please contact us to update your mailing address.
                                        </small>
                                    </div>
                                @endif


                                {{-- Everything below is wrapped so we can hide it until address is complete --}}
                                <div id="paymentWrapper" class="{{ $hasAddress ? '' : 'd-none' }}">
                                    {{-- Top toggle buttons --}}
                                    <div class="payment-options mt-3 mb-3">
                                        <div class="row">
                                            <div class="col-6">
                                                <button type="button"
                                                        class="btn btn-outline-secondary btn-100 py-3"
                                                        id="invoiceButton">
                                                    Invoice Me
                                                </button>
                                            </div>
                                            <div class="col-6">
                                                <button type="button"
                                                        class="btn btn-outline-primary btn-100 py-3"
                                                        id="cardButton">
                                                    Pay by Card
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- INVOICE SECTION --}}
                                    <div id="invoiceSection" class="d-none">
                                        <h6><b>Invoicing details</b></h6>
                                        <p class="text-muted mb-2">
                                            We’ll generate an invoice so you can pay by check. Your course access
                                            will become available within 5 days after payment is received.
                                        </p>

                                        <div class="mb-3">
                                            <label class="form-label">Name</label>
                                            <input type="text"
                                                   class="form-control"
                                                   value="{{ $user->name ?? '' }}"
                                                   readonly>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email"
                                                   class="form-control"
                                                   value="{{ $user->email ?? '' }}"
                                                   readonly>
                                        </div>

                                        <div class="alert alert-light border mt-2">
                                            <div class="d-flex justify-content-between">
                                                <span><strong>Invoice Total</strong></span>
                                                <span><strong>${{ number_format($courseCost, 2) }}</strong></span>
                                            </div>
                                            <small class="text-muted d-block mt-1">
                                                (No processing fees if paid by check)
                                            </small>
                                        </div>
                                    </div>

                                    {{-- CARD SECTION (shown by default when address is present) --}}
                                    <div id="cardSection" class="{{ $hasAddress ? '' : 'd-none' }}">
                                        <div class="mb-3">
                                            <label class="form-label">Name on card</label>
                                            <input
                                                id="card_name"
                                                class="form-control"
                                                name="{{ $TAKE_PAYMENT ? 'card_name' : '__demo_card_name' }}"
                                                value="{{ $user->name ?? '' }}"
                                                autocomplete="cc-name"
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
                                            <br /><br />
                                            <div class="invalid-feedback">Please enter your billing ZIP/postal code.</div>
                                        </div>

                                        {{-- 💳 Total including card fees --}}
                                        <div class="alert alert-light border mt-3">
                                            <div class="d-flex justify-content-between">
                                                <span>Course</span>
                                                <span>${{ number_format($courseCost, 2) }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span>Fees</span>
                                                <span>${{ number_format($fees, 2) }}</span>
                                            </div>
                                            <hr class="my-2">
                                            <div class="d-flex justify-content-between">
                                                <span><strong>Total</strong></span>
                                                <span><strong>${{ number_format($cardTotal, 2) }}</strong></span>
                                            </div>
                                        </div>

                                        <small class="text-muted d-block mt-3">
                                            <small class="text-muted d-block mt-2">
                                                By clicking <strong>Pay &amp; Register</strong>, you authorize Merchants Exchange
                                                to charge your card for the total amount shown and you agree to our
                                                <a href="{{ route('terms') }}" target="_blank" rel="noopener">Terms of Use</a>,
                                                <a href="{{ route('privacy') }}" target="_blank" rel="noopener">Privacy Policy</a>,
                                                and refund policy.
                                            </small>

                                            <br><br>
                                            Note: This is a placeholder form. This modal will continue with any card info and pressing "Pay &amp; Register"
                                            until Stripe is wired up.
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button"
                                        class="btn btn-outline-secondary"
                                        data-bs-dismiss="modal">
                                    Cancel
                                </button>

                                <button id="payRegisterSubmit"
                                        type="submit"
                                        class="btn btn-primary"
                                    {{ $isRegistered ? 'disabled' : '' }}>
                                    {{ $isRegistered ? 'Already Registered' : 'Pay & Register' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <!-- /Pay Modal -->



        @endsection


        @section('scripts')
            <script>
                (function () {
                    const form = document.getElementById('payRegisterForm');
                    if (!form) return;

                    // Only ENFORCE validation when you're actually charging cards
                    const ENFORCE = {{ $TAKE_PAYMENT ? 'true' : 'false' }};
                    const HAS_ADDRESS_ON_LOAD = {{ $hasAddress ? 'true' : 'false' }};

                    const el = {
                        number: document.getElementById('card_number'),
                        exp:    document.getElementById('card_exp'),
                        cvc:    document.getElementById('card_cvc'),
                        name:   document.getElementById('card_name'),
                        zip:    document.getElementById('billing_zip'),
                    };

                    const addr = {
                        address: document.getElementById('addr_address'),
                        city:    document.getElementById('addr_city'),
                        state:   document.getElementById('addr_state'),
                        zip:     document.getElementById('addr_zip'),
                    };

                    const invoiceBtn     = document.getElementById('invoiceButton');
                    const cardBtn        = document.getElementById('cardButton');
                    const invoiceSection = document.getElementById('invoiceSection');
                    const cardSection    = document.getElementById('cardSection');
                    const paymentWrapper = document.getElementById('paymentWrapper');
                    const methodInput    = document.getElementById('payment_method');
                    const submitBtn      = document.getElementById('payRegisterSubmit');
                    const modalTitle     = document.getElementById('payRegisterModalLabel');
                    const addressNotice  = document.getElementById('addressNotice');

                    function addressFilled() {
                        if (HAS_ADDRESS_ON_LOAD) {
                            return true;
                        }

                        return Object.values(addr).every(input =>
                            input && input.value.trim().length > 0
                        );
                    }

                    function minimalCardFilled() {
                        return !!(
                            el.name && el.name.value.trim() &&
                            el.number && el.number.value.trim() &&
                            el.exp && el.exp.value.trim() &&
                            el.cvc && el.cvc.value.trim() &&
                            el.zip && el.zip.value.trim()
                        );
                    }

                    function updatePaymentVisibility() {
                        const hasAddr = addressFilled();

                        if (!hasAddr) {
                            // Hide payment options until address is complete
                            paymentWrapper?.classList.add('d-none');
                            if (addressNotice) {
                                addressNotice.textContent = 'Next: Choose a payment method.';
                            }
                        } else {
                            paymentWrapper?.classList.remove('d-none');
                            if (addressNotice) {
                                addressNotice.textContent = '';
                            }
                        }

                        updateSubmitState();
                    }

                    function updateSubmitState() {
                        if (!submitBtn || {{ $isRegistered ? 'true' : 'false' }}) return;

                        const hasAddr = addressFilled();
                        const method  = methodInput?.value || 'card';

                        if (!hasAddr) {
                            submitBtn.disabled = true;
                            return;
                        }

                        if (method === 'invoice') {
                            // address is filled, invoice is fine
                            submitBtn.disabled = false;
                        } else {
                            // card mode: require basic presence of all card fields
                            submitBtn.disabled = !minimalCardFilled();
                        }
                    }

                    function setMode(mode) {
                        if (!methodInput) return;
                        methodInput.value = mode;

                        const hasAddr = addressFilled();
                        if (!hasAddr) {
                            // Just in case; payment wrapper will be hidden anyway
                            invoiceSection?.classList.add('d-none');
                            cardSection?.classList.add('d-none');
                            updateSubmitState();
                            return;
                        }

                        if (mode === 'invoice') {
                            invoiceSection?.classList.remove('d-none');
                            cardSection?.classList.add('d-none');

                            invoiceBtn?.classList.remove('btn-outline-secondary');
                            invoiceBtn?.classList.add('btn-primary');

                            cardBtn?.classList.remove('btn-primary');
                            cardBtn?.classList.add('btn-outline-primary');

                            // 🧠 Header + button text for INVOICE
                            if (modalTitle) modalTitle.textContent = 'Register by Invoice';
                            if (submitBtn && !{{ $isRegistered ? 'true' : 'false' }}) {
                                submitBtn.textContent = 'Register';
                            }
                        } else { // card
                            cardSection?.classList.remove('d-none');
                            invoiceSection?.classList.add('d-none');

                            cardBtn?.classList.remove('btn-outline-primary');
                            cardBtn?.classList.add('btn-primary');

                            invoiceBtn?.classList.remove('btn-primary');
                            invoiceBtn?.classList.add('btn-outline-secondary');

                            // 🧠 Header + button text for CARD
                            if (modalTitle) modalTitle.textContent = 'Pay & Register';
                            if (submitBtn && !{{ $isRegistered ? 'true' : 'false' }}) {
                                submitBtn.textContent = 'Pay & Register';
                            }
                        }

                        updateSubmitState();
                    }

                    // Initial setup
                    if (HAS_ADDRESS_ON_LOAD) {
                        setMode('card');
                    } else {
                        // No address yet: hide payment stuff, keep button disabled
                        paymentWrapper?.classList.add('d-none');
                        if (submitBtn) submitBtn.disabled = true;
                    }

                    // Address input events
                    Object.values(addr).forEach(input => {
                        if (!input) return;
                        input.addEventListener('input', () => {
                            updatePaymentVisibility();
                        });
                    });

                    // Toggle buttons
                    invoiceBtn?.addEventListener('click', function (e) {
                        e.preventDefault();
                        setMode('invoice');
                    });

                    cardBtn?.addEventListener('click', function (e) {
                        e.preventDefault();
                        setMode('card');
                    });

                    // --- Card input helpers / formatting ---
                    if (el.number) el.number.addEventListener('input', function () {
                        this.value = this.value.replace(/[^\d]/g,'').replace(/(.{4})/g,'$1 ').trim();
                        this.classList.remove('is-invalid');
                        updateSubmitState();
                    });
                    if (el.exp) el.exp.addEventListener('input', function () {
                        let v = this.value.replace(/[^\d]/g,'').slice(0,4);
                        if (v.length >= 3) v = v.slice(0,2) + '/' + v.slice(2);
                        this.value = v;
                        this.classList.remove('is-invalid');
                        updateSubmitState();
                    });
                    [el.cvc, el.name, el.zip].forEach(i => i && i.addEventListener('input', () => {
                        i.classList.remove('is-invalid');
                        updateSubmitState();
                    }));

                    // --- Submit handler ---
                    form.addEventListener('submit', function (e) {
                        const method = methodInput?.value || 'card';

                        // Always ensure address is filled at submit time as a safety net
                        if (!addressFilled()) {
                            e.preventDefault();
                            alert('Please complete your mailing address before registering.');
                            return;
                        }

                        // If not enforcing yet OR mode is invoice, skip strict card validation
                        if (!ENFORCE || method === 'invoice') {
                            return;
                        }

                        const num = (el.number?.value || '').replace(/\s+/g,'');
                        if (!/^\d{12,19}$/.test(num) || !luhn(num)) {
                            return stop(e, el.number, 'Enter a valid card number.');
                        }
                        if (!/^(0[1-9]|1[0-2])\/\d{2}$/.test(el.exp?.value || '')) {
                            return stop(e, el.exp, 'Format must be MM/YY.');
                        }
                        if (!/^\d{3,4}$/.test(el.cvc?.value || '')) {
                            return stop(e, el.cvc, '3–4 digits.');
                        }
                    });

                    function stop(e, input, msg) {
                        e.preventDefault();
                        if (!input) return;
                        input.classList.add('is-invalid');
                        const fb = input.closest('.mb-3, .col-6')?.querySelector('.invalid-feedback');
                        if (fb) fb.textContent = msg;
                        input.focus();
                    }

                    function luhn(num) {
                        let sum = 0, dbl = false;
                        for (let i = num.length - 1; i >= 0; i--) {
                            let d = +num[i];
                            if (dbl) { d *= 2; if (d > 9) d -= 9; }
                            sum += d; dbl = !dbl;
                        }
                        return sum % 10 === 0;
                    }

                    updatePaymentVisibility();
                })();
            </script>
        @endsection



</x-home-fullscreen-index>
