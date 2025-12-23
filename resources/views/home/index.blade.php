<x-home-fullscreen-index>

    @section('page-title')
        {{$settings->app_name}}
    @endsection

    @section('description')
        Merchants Exchange Learning Management System
    @endsection

    @section('background-image')
            @php
                $bg = asset('storage/' . (($settings->home_background ?? null) ?: 'storage/app-images/home-banner-1.jpg'));
                $ver = @filemtime(public_path('storage/' . (($settings->home_background ?? null) ?: 'storage/app-images/home-banner-1.jpg')));
            @endphp

            {{ $bg }}?v={{ $ver }}
    @endsection

    @section('banner')
        <img src="{{asset('storage/app-images/home-banner-1.jpg')}}" class="img-responsive" alt="MEX Learning Banner" title="MEX Learning Banner"/>
    @endsection


    @section('style')
        <style>
            @media (max-width: 968px) {
                .home-slider .carousel-item h2,
                .home-slider .carousel-item h3 {
                    font-size: 110%
                }
            }
            .home-slider .carousel-item h2,
            .home-slider .carousel-item h3 {
                color: #ffffff;
                font-weight: 600;
                text-shadow: 3px 3px 5px rgba(0, 0, 0, 0.5); /* 3px horizontal, 3px vertical, 5px blur, semi-transparent black */
            }
            .home-slider .carousel-item p {
                color: #ffffff;
                font-weight: 600;
            }

            /* === PORTFOLIO SLIDER (4 across) === */
            .portfolio-slider-wrapper {
                position: relative;
                overflow: hidden;
                width: 100%;
            }

            .portfolio-slider-track {
                display: flex;
                transition: transform 0.5s ease;
                will-change: transform;
            }

            .portfolio-slide {
                flex: 0 0 25%;          /* 4 across on large screens */
                padding: 0.5rem;
                box-sizing: border-box;
            }

            /* 3 across on md */
            @media (max-width: 991.98px) {
                .portfolio-slide {
                    flex: 0 0 33.3333%;
                }
            }

            /* 2 across on sm */
            @media (max-width: 767.98px) {
                .portfolio-slide {
                    flex: 0 0 50%;
                }
            }

            /* 1 across on xs */
            @media (max-width: 575.98px) {
                .portfolio-slide {
                    flex: 0 0 100%;
                }
            }

            /* Slider arrows (inside the portfolio section) */
            .portfolio-slider-nav {
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                border: none;
                background: rgba(0, 0, 0, 0.45);
                color: #fff;
                font-size: 2rem;
                line-height: 1;
                padding: 0.25rem 0.75rem;
                cursor: pointer;
                z-index: 5;
            }
            .portfolio-slider-prev {
                left: 5px;
            }
            .portfolio-slider-next {
                right: 5px;
            }
            .portfolio-slider-nav:focus {
                outline: none;
            }

            /* Lightbox arrows (inside modal) */
            .portfolio-nav {
                position: absolute;          /* relative to .modal-body (position-relative) */
                top: 50%;
                transform: translateY(-50%);
                border: none;
                background: rgba(0, 0, 0, 0.45);
                color: #fff;
                font-size: 2.5rem;
                line-height: 1;
                padding: 0.25rem 0.75rem;
                cursor: pointer;
                z-index: 20;
            }

            .portfolio-prev {
                left: 10px;
            }

            .portfolio-next {
                right: 10px;
            }

            .portfolio-nav:focus {
                outline: none;
            }

            /* Before/After slider */
            @media (max-width: 768px) {
                .before-after-slider-wrapper .card-img-top {
                    height: 200px;
                }
                .ba-modal-panel img {
                    height: 240px;
                }
            }

            .before-after-slider-wrapper .card-img-top {
                width: 100%;
                height: 260px;
                object-fit: cover;
            }

            .ba-modal-panel img {
                width: 100%;
                height: 320px;
                object-fit: cover;
            }

            .before-after-slider-wrapper {
                position: relative;
                overflow: hidden;
                width: 100%;
            }

            .before-after-slider-track {
                display: flex;
                transition: transform 0.4s ease;
                will-change: transform;
            }

            .before-after-slide {
                flex: 0 0 100%;      /* 1 pair per "page" */
                padding: 0.5rem 0;
            }

            .before-card,
            .after-card {
                cursor: pointer;
            }

            /* Slider arrows */
            .before-after-slider-nav {
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                border: none;
                background: rgba(0, 0, 0, 0.45);
                color: #fff;
                font-size: 2rem;
                line-height: 1;
                padding: 0.25rem 0.75rem;
                cursor: pointer;
                z-index: 5;
            }
            .before-after-slider-prev {
                left: 10px;
            }
            .before-after-slider-next {
                right: 10px;
            }
            .before-after-slider-nav:focus {
                outline: none;
            }

            /* Modal arrows */
            .before-after-modal-nav {
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                border: none;
                background: rgba(0, 0, 0, 0.45);
                color: #fff;
                font-size: 2.5rem;
                line-height: 1;
                padding: 0.25rem 0.75rem;
                cursor: pointer;
                z-index: 20;
            }
            .before-after-modal-prev {
                left: 10px;
            }
            .before-after-modal-next {
                right: 10px;
            }
            .before-after-modal-nav:focus {
                outline: none;
            }

            /* Same height in slider */
            .before-after-slider-wrapper .card-img-top {
                width: 100%;
                height: 260px;       /* tweak to taste */
                object-fit: cover;
            }

            /* Same height in modal */
            .before-after-modal-track img {
                width: 100%;
                height: 320px;       /* tweak to taste */
                object-fit: cover;
            }

            /* Modal track */
            .before-after-modal-track-wrapper {
                overflow: hidden;
                width: 100%;
            }

            .before-after-modal-track {
                display: flex;
                transition: transform 0.4s ease;
                will-change: transform;
            }

            .before-after-modal-track.no-transition {
                transition: none;
            }

            .before-after-modal-slide {
                flex: 0 0 100%;      /* 1 pair per view */
                padding: 0.5rem 0;
            }

            /* Modal arrows */
            .before-after-modal-nav {
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                border: none;
                background: rgba(0, 0, 0, 0.45);
                color: #fff;
                font-size: 2.5rem;
                line-height: 1;
                padding: 0.25rem 0.75rem;
                cursor: pointer;
                z-index: 20;
            }
            .before-after-modal-prev {
                left: 10px;
            }
            .before-after-modal-next {
                right: 10px;
            }
            .before-after-modal-nav:focus {
                outline: none;
            }
            /* Same height in slider cards */
            .before-after-slider-wrapper .card-img-top {
                width: 100%;
                height: 260px;       /* tweak as you like */
                object-fit: cover;
            }

            /* Lightbox image */
            .ba-lightbox-image {
                max-height: 70vh;
                object-fit: contain;
            }

            /* Lightbox arrows */
            .ba-lightbox-nav {
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                border: none;
                background: rgba(0, 0, 0, 0.45);
                color: #fff;
                font-size: 2.5rem;
                line-height: 1;
                padding: 0.25rem 0.75rem;
                cursor: pointer;
                z-index: 20;
            }
            .ba-lightbox-prev {
                left: 10px;
            }
            .ba-lightbox-next {
                right: 10px;
            }
            .ba-lightbox-nav:focus {
                outline: none;
            }


        </style>
    @endsection


    @section('content')

        <div class="home-slider mt-5 cement-shadow">
            <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
                <!-- Indicators -->
                <div class="carousel-indicators hidden-xs">
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true"></button>
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
                </div>

                <!-- Slides -->
                <div class="carousel-inner">
                    <div class="carousel-item active" data-bs-interval="2000">
                        <img src="{{asset('storage/app-images/carousel-home-before.jpg')}}" class="d-block w-100 banner-img" alt="Banner 1">
                        <div class="carousel-caption">
                            <h2>Is it time for a refresh?</h2>
{{--                            <p>in Portland, OR</p>--}}
                        </div>
                    </div>

                    <div class="carousel-item" data-bs-interval="5000">
                        <img src="{{asset('storage/app-images/carousel-home-after.jpg')}}" class="d-block w-100 banner-img" alt="Banner 2">
                        <div class="carousel-caption">
                            <h2>Call us today!</h2>
                            <a href="tel:{{$settings->company_phone}}" class="btn btn-primary btn-center mt-2">Call Now</a>
{{--                            <p>This may showcase a specific course / lesson</p>--}}
                        </div>
                    </div>

                    {{-- 2: duplicate of first (loop helper) --}}
                    <div class="carousel-item carousel-duplicate-first" data-bs-interval="2000">
                        <img src="{{asset('storage/app-images/carousel-home-before.jpg')}}" class="d-block w-100 banner-img" alt="Banner 1 duplicate">
                        <div class="carousel-caption">
                            <h2>Is it time for a refresh?</h2>
                        </div>
                    </div>
                </div>

                <!-- Controls -->
                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span><span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>

        <!-- About Us -->
        <div class="white-back">
            <div class="row">
                <div class="col-12">
                    <h2 class="text-primary">About Us</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <h3>Premium Concrete Service around the Portland Area</h3>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                        Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
                        in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident,
                        sunt in culpa qui officia deserunt mollit anim id est laborum.
                    </p>
                </div>
                <div class="col-sm-6">
                    <h4 class="text-black text-center">Claim Your Free Quote Today!</h4>
                    <form action="#">
                        @csrf
                        @method('POST')

                        <div class="form-group">
                            <div class="row mt-3">
                                <div class="col-6">
                                    <label for="first_name">First Name</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name">
                                </div>
                                <div class="col-6">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name">
                                </div>
                                <div class="col-12 mt-3">
                                    <label for="phone">Phone</label>
                                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone">
                                </div>
                                <div class="col-12 mt-3">
                                    <label for="email">Email</label>
                                    <input type="text" class="form-control" id="email" name="email" placeholder="Email">
                                </div>
                                <div class="col-12 mt-3">
                                    <button class="btn-primary btn-block btn-lg btn-submit" type="submit">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /About Us -->


        <!-- Portfolio -->
        <div class="white-back">
            <div class="row">
                <div class="col-12">
                    <h2 class="text-primary">Our Portfolio</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div id="portfolioSlider" class="portfolio-slider-wrapper">
                        <button class="portfolio-slider-nav portfolio-slider-prev" aria-label="Previous">
                            &#10094;
                        </button>
                        <button class="portfolio-slider-nav portfolio-slider-next" aria-label="Next">
                            &#10095;
                        </button>

                        <div class="portfolio-slider-track">
                            {{-- 1 --}}
                            <div class="portfolio-slide">
                                <div class="card border-0 shadow-sm h-100">
                                    <img src="{{ asset('storage/portfolio/port-images/img-11.jpg') }}"
                                         class="card-img-top"
                                         alt="Freshly poured concrete driveway in front of a home">
                                    <div class="card-body p-3">
                                        <h5 class="card-title mb-1">Stamped Concrete Slab</h5>
                                        <p class="card-text small text-muted mb-0">
                                            Decorative stamped concrete driveway featuring a textured stone-style pattern for added traction and visual appeal. Combines durability with an upscale look that elevates curb appeal.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- 2 --}}
                            <div class="portfolio-slide">
                                <div class="card border-0 shadow-sm h-100">
                                    <img src="{{ asset('storage/portfolio/final/img-2.jpg') }}"
                                         class="card-img-top"
                                         alt="Stamped concrete patio with outdoor furniture">
                                    <div class="card-body p-3">
                                        <h5 class="card-title mb-1">Smooth-Finish Concrete Slab</h5>
                                        <p class="card-text small text-muted mb-0">
                                            Freshly poured smooth-finish concrete slab for a clean, durable surface that’s perfect for garage aprons and driveway extensions. Built for long-lasting performance with a crisp, professional look.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- 4 --}}
                            <div class="portfolio-slide">
                                <div class="card border-0 shadow-sm h-100">
                                    <img src="{{ asset('storage/portfolio/port-images/img-12.jpg') }}"
                                         class="card-img-top"
                                         alt="Concrete stairs and entry landing">
                                    <div class="card-body p-3">
                                        <h5 class="card-title mb-1">Stamped Concrete Walkway</h5>
                                        <p class="card-text small text-muted mb-0">
                                            Curved stamped concrete walkway featuring a natural stone pattern for a custom, high-end look. Designed for durability and smooth foot traffic while enhancing the home’s landscape and curb appeal.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- 4 --}}
                            <div class="portfolio-slide">
                                <div class="card border-0 shadow-sm h-100">
                                    <img src="{{ asset('storage/portfolio/port-images/img-13.jpg') }}"
                                         class="card-img-top"
                                         alt="Concrete stairs and entry landing">
                                    <div class="card-body p-3">
                                        <h5 class="card-title mb-1">Stamped Concrete Patio & Landing</h5>
                                        <p class="card-text small text-muted mb-0">
                                            Decorative stamped concrete landing with a natural stone pattern that adds texture and curb appeal. A durable, low-maintenance surface that creates a welcoming front entry.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- 5 --}}
                            <div class="portfolio-slide">
                                <div class="card border-0 shadow-sm h-100">
                                    <img src="{{ asset('storage/portfolio/final/img-5.jpg') }}"
                                         class="card-img-top"
                                         alt="Backyard concrete patio surrounding a fire pit">
                                    <div class="card-body p-3">
                                        <h5 class="card-title mb-1">Polished Concrete Slab</h5>
                                        <p class="card-text small text-muted mb-0">
                                            Smooth, polished concrete slab ideal for workshops, garages, or covered entertaining spaces. Provides a clean, durable surface built to handle heavy use while maintaining a sleek, modern appearance.
                                        </p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <a href="{{route('home.portfolio')}}" class="btn btn-primary btn-center">View Portfolio</a>
                </div>
            </div>
        </div>
        <!-- /Portfolio -->


        <!-- Before-After -->
        <div class="white-back">
            <div class="row">
                <div class="col-12">
                    <h2 class="text-primary">Before &amp; After</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div id="beforeAfterSlider" class="before-after-slider-wrapper">
                        <button class="before-after-slider-nav before-after-slider-prev" aria-label="Previous">
                            &#10094;
                        </button>
                        <button class="before-after-slider-nav before-after-slider-next" aria-label="Next">
                            &#10095;
                        </button>

                        <div class="before-after-slider-track">


                            {{-- Set 4 --}}
                            <div class="before-after-slide">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="card border-0 shadow-sm h-100 before-card">
                                            <img src="{{ asset('storage/portfolio/before-after/before-after-5.jpg') }}"
                                                 class="card-img-top"
                                                 alt="Walkway before">
                                            <div class="card-body p-2 text-center">
                                                <span class="badge bg-secondary">Before</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card border-0 shadow-sm h-100 after-card">
                                            <img src="{{ asset('storage/portfolio/before-after/before-after-5a.jpg') }}"
                                                 class="card-img-top"
                                                 alt="Walkway after">
                                            <div class="card-body p-2 text-center">
                                                <span class="badge bg-primary">After</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Set 1 --}}
                            <div class="before-after-slide">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="card border-0 shadow-sm h-100 before-card">
                                            <img src="{{ asset('storage/portfolio/before-after/before-after-1.jpg') }}"
                                                 class="card-img-top"
                                                 alt="Driveway before">
                                            <div class="card-body p-2 text-center">
                                                <span class="badge bg-secondary">Before</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card border-0 shadow-sm h-100 after-card">
                                            <img src="{{ asset('storage/portfolio/before-after/before-after-1a.jpg') }}"
                                                 class="card-img-top"
                                                 alt="Driveway after">
                                            <div class="card-body p-2 text-center">
                                                <span class="badge bg-primary">After</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Set 2 --}}
                            <div class="before-after-slide">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="card border-0 shadow-sm h-100 before-card">
                                            <img src="{{ asset('storage/portfolio/before-after/before-after-2.jpg') }}"
                                                 class="card-img-top"
                                                 alt="Patio before">
                                            <div class="card-body p-2 text-center">
                                                <span class="badge bg-secondary">Before</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card border-0 shadow-sm h-100 after-card">
                                            <img src="{{ asset('storage/portfolio/before-after/before-after-2a.jpg') }}"
                                                 class="card-img-top"
                                                 alt="Patio after">
                                            <div class="card-body p-2 text-center">
                                                <span class="badge bg-primary">After</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Set 3 --}}
                            <div class="before-after-slide">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="card border-0 shadow-sm h-100 before-card">
                                            <img src="{{ asset('storage/portfolio/before-after/before-after-3.jpg') }}"
                                                 class="card-img-top"
                                                 alt="Walkway before">
                                            <div class="card-body p-2 text-center">
                                                <span class="badge bg-secondary">Before</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card border-0 shadow-sm h-100 after-card">
                                            <img src="{{ asset('storage/portfolio/before-after/before-after-3a.jpg') }}"
                                                 class="card-img-top"
                                                 alt="Walkway after">
                                            <div class="card-body p-2 text-center">
                                                <span class="badge bg-primary">After</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Add more sets here in the same pattern --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Before-After -->


        <!-- Before/After Single-Image Lightbox -->
        <div class="modal fade" id="baImageModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content bg-dark text-white border-0">
                    <div class="modal-body p-3 position-relative" id="baImageModalBody">

                        <!-- Close -->
                        <button type="button"
                                class="btn-close btn-close-white position-absolute"
                                style="top: 10px; right: 20px; z-index: 30;"
                                data-bs-dismiss="modal"
                                aria-label="Close"></button>

                        <!-- Arrows -->
                        <button type="button"
                                class="ba-lightbox-nav ba-lightbox-prev"
                                aria-label="Previous">
                            &#10094;
                        </button>
                        <button type="button"
                                class="ba-lightbox-nav ba-lightbox-next"
                                aria-label="Next">
                            &#10095;
                        </button>

                        <!-- Image + label -->
                        <div class="d-flex flex-column align-items-center">
                            <img id="baLightboxImage"
                                 src=""
                                 alt=""
                                 class="img-fluid ba-lightbox-image mb-2" />

                            <span id="baLightboxLabel" class="badge bg-secondary">
                    Before
                </span>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- /Before/After Single-Image Lightbox -->





        @endsection

        @section('scripts')
            <script>
                $(document).ready(function() {
                    $("input[name='phone']").keyup(function() {
                        $(this).val($(this).val().replace(/^(\d{3})(\d{3})(\d+)$/, "$1-$2-$3"));
                    });
                });
            </script>

            {{-- LIGHTBOX SCRIPT (keep your existing one here, unchanged) --}}
            <script>
                (function () {
                    function initBeforeAfterLightbox() {
                        var sliderWrapper = document.getElementById('beforeAfterSlider');
                        var modalEl       = document.getElementById('baImageModal');
                        if (!sliderWrapper || !modalEl) return;

                        var bodyEl   = document.getElementById('baImageModalBody');
                        var imgEl    = document.getElementById('baLightboxImage');
                        var labelEl  = document.getElementById('baLightboxLabel');
                        var prevBtn  = modalEl.querySelector('.ba-lightbox-prev');
                        var nextBtn  = modalEl.querySelector('.ba-lightbox-next');

                        // Build pairs from slider
                        var sliderSlides = Array.prototype.slice.call(
                            sliderWrapper.querySelectorAll('.before-after-slide')
                        );
                        if (!sliderSlides.length) return;

                        var pairs = [];
                        sliderSlides.forEach(function (slide) {
                            var beforeImg = slide.querySelector('.before-card img');
                            var afterImg  = slide.querySelector('.after-card img');
                            if (!beforeImg || !afterImg) return;

                            var pairIndex = pairs.length;

                            pairs.push({
                                before: {
                                    src: beforeImg.getAttribute('src'),
                                    alt: beforeImg.getAttribute('alt') || ''
                                },
                                after: {
                                    src: afterImg.getAttribute('src'),
                                    alt: afterImg.getAttribute('alt') || ''
                                }
                            });

                            // Click handlers — each image opens its own pair
                            beforeImg.style.cursor = 'pointer';
                            beforeImg.addEventListener('click', function () {
                                openModal(pairIndex, 0); // 0 = before
                            });

                            afterImg.style.cursor = 'pointer';
                            afterImg.addEventListener('click', function () {
                                openModal(pairIndex, 1); // 1 = after
                            });
                        });

                        if (!pairs.length) return;

                        var currentPairIndex = 0;
                        var currentSideIndex = 0; // 0 = before, 1 = after

                        function updateBadge() {
                            if (currentSideIndex === 0) {
                                labelEl.textContent = 'Before';
                                labelEl.classList.remove('bg-primary');
                                labelEl.classList.add('bg-secondary');
                            } else {
                                labelEl.textContent = 'After';
                                labelEl.classList.remove('bg-secondary');
                                labelEl.classList.add('bg-primary');
                            }
                        }

                        function showCurrent() {
                            var pair = pairs[currentPairIndex];
                            var side = currentSideIndex === 0 ? pair.before : pair.after;

                            imgEl.src = side.src;
                            imgEl.alt = side.alt;
                            updateBadge();
                        }

                        function nextInPair() {
                            currentSideIndex = (currentSideIndex + 1) % 2; // 0 -> 1, 1 -> 0
                            showCurrent();
                        }

                        function prevInPair() {
                            currentSideIndex = (currentSideIndex + 1) % 2; // same toggle both ways
                            showCurrent();
                        }

                        function openModal(pairIndex, sideIndex) {
                            currentPairIndex = pairIndex;
                            currentSideIndex = sideIndex === 1 ? 1 : 0;
                            showCurrent();

                            if (window.bootstrap && bootstrap.Modal) {
                                var instance = bootstrap.Modal.getOrCreateInstance(modalEl);
                                instance.show();
                            } else {
                                modalEl.classList.add('show');
                                modalEl.style.display = 'block';
                                modalEl.removeAttribute('aria-hidden');
                                document.body.classList.add('modal-open');
                            }
                        }

                        // Arrows: just flip between before/after in the current pair
                        if (nextBtn) nextBtn.addEventListener('click', nextInPair);
                        if (prevBtn) prevBtn.addEventListener('click', prevInPair);

                        // Keyboard left/right within the pair
                        document.addEventListener('keydown', function (e) {
                            if (!modalEl.classList.contains('show')) return;

                            if (e.key === 'ArrowLeft') {
                                prevInPair();
                            } else if (e.key === 'ArrowRight') {
                                nextInPair();
                            }
                        });

                        // Swipe / drag inside modal body
                        var startX = 0;
                        var startY = 0;
                        var isPointerDown = false;
                        var SWIPE_THRESHOLD = 40;

                        function startDrag(clientX, clientY, target) {
                            // Don't start from arrows or close button
                            if (target && target.closest && (target.closest('.ba-lightbox-nav') || target.closest('.btn-close'))) {
                                return;
                            }
                            isPointerDown = true;
                            startX = clientX;
                            startY = clientY;
                        }

                        function endDrag(clientX, clientY) {
                            if (!isPointerDown) return;
                            isPointerDown = false;

                            var dx = clientX - startX;
                            var dy = clientY - startY;

                            if (Math.abs(dx) < SWIPE_THRESHOLD || Math.abs(dx) < Math.abs(dy)) {
                                return;
                            }

                            if (dx < 0) {
                                nextInPair();
                            } else {
                                prevInPair();
                            }
                        }

                        // Touch
                        bodyEl.addEventListener('touchstart', function (e) {
                            if (!e.touches || !e.touches.length) return;
                            var t = e.touches[0];
                            startDrag(t.clientX, t.clientY, e.target);
                        }, { passive: true });

                        bodyEl.addEventListener('touchend', function (e) {
                            if (!e.changedTouches || !e.changedTouches.length) return;
                            var t = e.changedTouches[0];
                            endDrag(t.clientX, t.clientY);
                        });

                        // Mouse
                        bodyEl.addEventListener('mousedown', function (e) {
                            e.preventDefault();
                            startDrag(e.clientX, e.clientY, e.target);
                        });

                        window.addEventListener('mouseup', function (e) {
                            endDrag(e.clientX, e.clientY);
                        });
                    }

                    if (document.readyState === 'loading') {
                        document.addEventListener('DOMContentLoaded', initBeforeAfterLightbox);
                    } else {
                        initBeforeAfterLightbox();
                    }
                })();
            </script>



            {{-- PORTFOLIO SLIDER SCRIPT (with swipe/drag) --}}
            <script>
                (function () {
                    function initPortfolioSlider() {
                        var wrapper = document.getElementById('portfolioSlider');
                        if (!wrapper) return;

                        var track  = wrapper.querySelector('.portfolio-slider-track');
                        var slides = Array.prototype.slice.call(
                            wrapper.querySelectorAll('.portfolio-slide')
                        );
                        if (!slides.length) return;

                        var prevBtn = wrapper.querySelector('.portfolio-slider-prev');
                        var nextBtn = wrapper.querySelector('.portfolio-slider-next');

                        var currentIndex   = 0;
                        var autoplayId     = null;
                        var autoplayDelay  = 4000; // ms

                        // ----- helpers for layout -----
                        function getVisibleCount() {
                            var w = window.innerWidth;

                            if (w < 576)      return 1;   // xs
                            if (w < 768)      return 2;   // sm
                            if (w < 992)      return 3;   // md
                            return 4;                      // lg+
                        }

                        function goTo(index) {
                            if (!slides.length) return;

                            var visible   = getVisibleCount();
                            var maxIndex  = Math.max(slides.length - visible, 0);

                            // Clamp index so we don't scroll into blank space
                            if (index < 0)        index = 0;
                            if (index > maxIndex) index = maxIndex;

                            currentIndex = index;

                            var maxOffset = track.scrollWidth - wrapper.clientWidth;

                            // If all slides fit (no need to scroll), just reset transform
                            if (maxOffset <= 0) {
                                track.style.transform = 'translateX(0px)';
                                return;
                            }

                            var targetOffset = slides[currentIndex].offsetLeft;
                            var offset       = Math.min(targetOffset, maxOffset);

                            track.style.transform = 'translateX(' + (-offset) + 'px)';
                        }

                        function next() {
                            var visible  = getVisibleCount();
                            var maxIndex = Math.max(slides.length - visible, 0);

                            if (currentIndex >= maxIndex) {
                                // Wrap back to start
                                goTo(0);
                            } else {
                                goTo(currentIndex + 1);
                            }
                        }

                        function prev() {
                            var visible  = getVisibleCount();
                            var maxIndex = Math.max(slides.length - visible, 0);

                            if (currentIndex <= 0) {
                                // Wrap to last "page"
                                goTo(maxIndex);
                            } else {
                                goTo(currentIndex - 1);
                            }
                        }

                        function startAutoplay() {
                            stopAutoplay();

                            // If there aren't enough slides to actually scroll, don't autoplay
                            if (slides.length <= getVisibleCount()) {
                                if (prevBtn) prevBtn.style.display = 'none';
                                if (nextBtn) nextBtn.style.display = 'none';
                                return;
                            }

                            if (prevBtn) prevBtn.style.display = '';
                            if (nextBtn) nextBtn.style.display = '';

                            autoplayId = setInterval(next, autoplayDelay);
                        }

                        function stopAutoplay() {
                            if (autoplayId) {
                                clearInterval(autoplayId);
                                autoplayId = null;
                            }
                        }

                        // ----- arrows -----
                        if (nextBtn) {
                            nextBtn.addEventListener('click', function () {
                                next();
                                startAutoplay(); // restart timer on manual click
                            });
                        }

                        if (prevBtn) {
                            prevBtn.addEventListener('click', function () {
                                prev();
                                startAutoplay();
                            });
                        }

                        // ----- swipe / drag support -----
                        var startX = 0;
                        var startY = 0;
                        var isPointerDown = false;

                        var SWIPE_THRESHOLD = 40; // px minimum to count as swipe

                        function onPointerDown(clientX, clientY, target) {
                            // Ignore drags starting on nav buttons
                            if (target && target.closest && target.closest('.portfolio-slider-nav')) {
                                return;
                            }
                            isPointerDown = true;
                            startX = clientX;
                            startY = clientY;
                        }

                        function onPointerUp(clientX, clientY) {
                            if (!isPointerDown) return;
                            isPointerDown = false;

                            var dx = clientX - startX;
                            var dy = clientY - startY;

                            // Only trigger on mostly horizontal swipes
                            if (Math.abs(dx) < SWIPE_THRESHOLD || Math.abs(dx) < Math.abs(dy)) {
                                return;
                            }

                            if (dx < 0) {
                                // swipe left -> next
                                next();
                            } else {
                                // swipe right -> prev
                                prev();
                            }

                            startAutoplay(); // keep autoplay alive after gesture
                        }

                        // Touch (mobile)
                        wrapper.addEventListener('touchstart', function (e) {
                            if (!e.touches || !e.touches.length) return;
                            var t = e.touches[0];
                            onPointerDown(t.clientX, t.clientY, e.target);
                        }, { passive: true });

                        wrapper.addEventListener('touchend', function (e) {
                            if (!e.changedTouches || !e.changedTouches.length) return;
                            var t = e.changedTouches[0];
                            onPointerUp(t.clientX, t.clientY);
                        });

                        // Mouse (desktop)
                        wrapper.addEventListener('mousedown', function (e) {
                            onPointerDown(e.clientX, e.clientY, e.target);
                        });

                        window.addEventListener('mouseup', function (e) {
                            onPointerUp(e.clientX, e.clientY);
                        });

                        // Realign on resize so the current item stays aligned
                        window.addEventListener('resize', function () {
                            goTo(currentIndex);
                        });

                        // Init
                        goTo(0);
                        startAutoplay();
                    }

                    if (document.readyState === 'loading') {
                        document.addEventListener('DOMContentLoaded', initPortfolioSlider);
                    } else {
                        initPortfolioSlider();
                    }
                })();
            </script>

            {{-- BEFORE AFTER SLIDER (with swipe/drag) --}}
            <script>
                (function () {
                    function initBeforeAfterSlider() {
                        var wrapper = document.getElementById('beforeAfterSlider');
                        if (!wrapper) return;

                        var track  = wrapper.querySelector('.before-after-slider-track');
                        var slides = Array.prototype.slice.call(
                            wrapper.querySelectorAll('.before-after-slide')
                        );
                        if (!slides.length) return;

                        var prevBtn = wrapper.querySelector('.before-after-slider-prev');
                        var nextBtn = wrapper.querySelector('.before-after-slider-next');

                        var currentIndex   = 0;
                        var isDragging     = false;
                        var startX         = 0;
                        var baseTranslate  = 0;

                        function getSlideWidth() {
                            return wrapper.clientWidth; // each slide is 100% width
                        }

                        function setTranslate(x) {
                            track.style.transform = 'translateX(' + x + 'px)';
                        }

                        function goTo(index) {
                            if (!slides.length) return;

                            if (index < 0) {
                                index = slides.length - 1;
                            } else if (index >= slides.length) {
                                index = 0;
                            }

                            currentIndex = index;

                            var offset = -currentIndex * getSlideWidth();
                            // use smooth transition
                            track.classList.remove('no-transition');
                            setTranslate(offset);
                        }

                        function next() {
                            goTo(currentIndex + 1);
                        }

                        function prev() {
                            goTo(currentIndex - 1);
                        }

                        // --- Arrows ---
                        if (nextBtn) {
                            nextBtn.addEventListener('click', next);
                        }
                        if (prevBtn) {
                            prevBtn.addEventListener('click', prev);
                        }

                        // --- Drag helpers ---
                        var DRAG_THRESHOLD_RATIO = 0.15; // 15% of width

                        function startDrag(clientX, target) {
                            // don’t start drag on nav buttons
                            if (target && target.closest && target.closest('.before-after-slider-nav')) {
                                return;
                            }

                            isDragging = true;
                            startX = clientX;
                            baseTranslate = -currentIndex * getSlideWidth();

                            // disable transition while dragging
                            track.classList.add('no-transition');
                        }

                        function moveDrag(clientX) {
                            if (!isDragging) return;

                            var dx = clientX - startX;
                            var newTranslate = baseTranslate + dx;
                            setTranslate(newTranslate);
                        }

                        function endDrag(clientX) {
                            if (!isDragging) return;
                            isDragging = false;

                            var dx = clientX - startX;
                            var slideWidth = getSlideWidth();
                            var threshold = slideWidth * DRAG_THRESHOLD_RATIO;

                            if (dx < -threshold) {
                                // dragged left enough -> next
                                currentIndex++;
                            } else if (dx > threshold) {
                                // dragged right enough -> prev
                                currentIndex--;
                            }

                            goTo(currentIndex);
                        }

                        // --- Touch events ---
                        wrapper.addEventListener('touchstart', function (e) {
                            if (!e.touches || !e.touches.length) return;
                            var t = e.touches[0];
                            startDrag(t.clientX, e.target);
                        }, { passive: true });

                        wrapper.addEventListener('touchmove', function (e) {
                            if (!isDragging || !e.touches || !e.touches.length) return;
                            var t = e.touches[0];
                            moveDrag(t.clientX);
                        }, { passive: false });

                        wrapper.addEventListener('touchend', function (e) {
                            if (!e.changedTouches || !e.changedTouches.length) return;
                            var t = e.changedTouches[0];
                            endDrag(t.clientX);
                        });

                        // --- Mouse events ---
                        wrapper.addEventListener('mousedown', function (e) {
                            e.preventDefault(); // prevent text/image selection
                            startDrag(e.clientX, e.target);
                        });

                        window.addEventListener('mousemove', function (e) {
                            if (!isDragging) return;
                            moveDrag(e.clientX);
                        });

                        window.addEventListener('mouseup', function (e) {
                            if (!isDragging) return;
                            endDrag(e.clientX);
                        });

                        // Realign on resize
                        window.addEventListener('resize', function () {
                            goTo(currentIndex);
                        });

                        // Init position
                        goTo(0);
                    }

                    if (document.readyState === 'loading') {
                        document.addEventListener('DOMContentLoaded', initBeforeAfterSlider);
                    } else {
                        initBeforeAfterSlider();
                    }
                })();
            </script>


            {{-- BEFORE AFTER MODAL --}}
            <script>
                (function () {
                    function initBeforeAfterModal() {
                        var sliderWrapper = document.getElementById('beforeAfterSlider');
                        var modalEl       = document.getElementById('beforeAfterModal');
                        if (!sliderWrapper || !modalEl) return;

                        var slides = Array.prototype.slice.call(
                            sliderWrapper.querySelectorAll('.before-after-slide')
                        );
                        if (!slides.length) return;

                        // Collect pairs
                        var pairs = [];
                        slides.forEach(function (slide, index) {
                            var beforeImg = slide.querySelector('.before-card img');
                            var afterImg  = slide.querySelector('.after-card img');
                            if (!beforeImg || !afterImg) return;

                            pairs.push({
                                beforeSrc: beforeImg.getAttribute('src'),
                                beforeAlt: beforeImg.getAttribute('alt') || '',
                                afterSrc: afterImg.getAttribute('src'),
                                afterAlt: afterImg.getAttribute('alt') || ''
                            });

                            // Click either card opens modal for this index
                            slide.querySelectorAll('.before-card, .after-card').forEach(function (card) {
                                card.style.cursor = 'pointer';
                                card.addEventListener('click', function () {
                                    openModal(index);
                                });
                            });
                        });

                        if (!pairs.length) return;

                        var beforeImgEl = document.getElementById('baModalBeforeImage');
                        var afterImgEl  = document.getElementById('baModalAfterImage');
                        var prevBtn     = modalEl.querySelector('.before-after-modal-prev');
                        var nextBtn     = modalEl.querySelector('.before-after-modal-next');
                        var closeBtn    = modalEl.querySelector('.btn-close');
                        var bodyEl      = document.getElementById('beforeAfterModalBody');

                        var currentIndex = 0;
                        var modalInstance = null;

                        function showPair(index) {
                            if (index < 0) {
                                index = pairs.length - 1;
                            } else if (index >= pairs.length) {
                                index = 0;
                            }
                            currentIndex = index;

                            var pair = pairs[currentIndex];
                            beforeImgEl.src = pair.beforeSrc;
                            beforeImgEl.alt = pair.beforeAlt;
                            afterImgEl.src  = pair.afterSrc;
                            afterImgEl.alt  = pair.afterAlt;
                        }

                        function openModal(index) {
                            showPair(index);

                            if (window.bootstrap && bootstrap.Modal) {
                                modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
                                modalInstance.show();
                            } else {
                                modalEl.classList.add('show');
                                modalEl.style.display = 'block';
                                modalEl.removeAttribute('aria-hidden');
                                document.body.classList.add('modal-open');
                            }
                        }

                        function closeModal() {
                            if (window.bootstrap && bootstrap.Modal) {
                                (bootstrap.Modal.getInstance(modalEl) || bootstrap.Modal.getOrCreateInstance(modalEl)).hide();
                            } else {
                                modalEl.classList.remove('show');
                                modalEl.style.display = 'none';
                                modalEl.setAttribute('aria-hidden', 'true');
                                document.body.classList.remove('modal-open');
                                document.querySelectorAll('.modal-backdrop').forEach(function (b) { b.remove(); });
                            }
                        }

                        if (prevBtn) {
                            prevBtn.addEventListener('click', function () {
                                showPair(currentIndex - 1);
                            });
                        }
                        if (nextBtn) {
                            nextBtn.addEventListener('click', function () {
                                showPair(currentIndex + 1);
                            });
                        }
                        if (closeBtn) {
                            closeBtn.addEventListener('click', function () {
                                closeModal();
                            });
                        }

                        // Mouse wheel scroll inside modal
                        bodyEl.addEventListener('wheel', function (e) {
                            var delta = Math.abs(e.deltaX) > Math.abs(e.deltaY) ? e.deltaX : e.deltaY;

                            if (Math.abs(delta) < 5) return;

                            e.preventDefault();
                            if (delta > 0) {
                                showPair(currentIndex + 1);
                            } else {
                                showPair(currentIndex - 1);
                            }
                        }, { passive: false });


                        // Keyboard nav
                        document.addEventListener('keydown', function (e) {
                            if (!modalEl.classList.contains('show')) return;

                            if (e.key === 'ArrowLeft') {
                                showPair(currentIndex - 1);
                            } else if (e.key === 'ArrowRight') {
                                showPair(currentIndex + 1);
                            } else if (e.key === 'Escape') {
                                closeModal();
                            }
                        });

                        // Swipe / drag inside modal
                        var startX = 0;
                        var startY = 0;
                        var isPointerDown = false;
                        var SWIPE_THRESHOLD = 40;

                        function onPointerDown(cx, cy) {
                            isPointerDown = true;
                            startX = cx;
                            startY = cy;
                        }

                        function onPointerUp(cx, cy) {
                            if (!isPointerDown) return;
                            isPointerDown = false;

                            var dx = cx - startX;
                            var dy = cy - startY;

                            if (Math.abs(dx) < SWIPE_THRESHOLD || Math.abs(dx) < Math.abs(dy)) {
                                return;
                            }

                            if (dx < 0) {
                                showPair(currentIndex + 1);
                            } else {
                                showPair(currentIndex - 1);
                            }
                        }

                        bodyEl.addEventListener('touchstart', function (e) {
                            if (!e.touches || !e.touches.length) return;
                            var t = e.touches[0];
                            onPointerDown(t.clientX, t.clientY);
                        }, { passive: true });

                        bodyEl.addEventListener('touchend', function (e) {
                            if (!e.changedTouches || !e.changedTouches.length) return;
                            var t = e.changedTouches[0];
                            onPointerUp(t.clientX, t.clientY);
                        });

                        bodyEl.addEventListener('mousedown', function (e) {
                            onPointerDown(e.clientX, e.clientY);
                        });

                        window.addEventListener('mouseup', function (e) {
                            onPointerUp(e.clientX, e.clientY);
                        });
                    }

                    if (document.readyState === 'loading') {
                        document.addEventListener('DOMContentLoaded', initBeforeAfterModal);
                    } else {
                        initBeforeAfterModal();
                    }
                })();
            </script>


        @endsection


</x-home-fullscreen-index>
