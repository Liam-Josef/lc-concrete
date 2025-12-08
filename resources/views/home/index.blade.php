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
                            <a href="{{$settings->company_phone}}" class="btn btn-primary btn-center mt-2">Call Now</a>
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
                                        <h5 class="card-title mb-1">New Concrete Driveway</h5>
                                        <p class="card-text small text-muted mb-0">Clean, durable finish for everyday use.</p>
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
                                        <h5 class="card-title mb-1">Stamped Concrete Patio</h5>
                                        <p class="card-text small text-muted mb-0">Stamped pattern to mimic stone.</p>
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
                                        <h5 class="card-title mb-1">Entry Stairs &amp; Landing</h5>
                                        <p class="card-text small text-muted mb-0">Safe, level steps to the front door.</p>
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
                                        <h5 class="card-title mb-1">Entry Stairs &amp; Landing</h5>
                                        <p class="card-text small text-muted mb-0">Safe, level steps to the front door.</p>
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
                                        <h5 class="card-title mb-1">Backyard Entertaining Space</h5>
                                        <p class="card-text small text-muted mb-0">Perfect for gatherings &amp; BBQs.</p>
                                    </div>
                                </div>
                            </div>

                            {{-- 6 --}}
                            <div class="portfolio-slide">
                                <div class="card border-0 shadow-sm h-100">
                                    <img src="{{ asset('storage/portfolio/final/img-6.jpg') }}"
                                         class="card-img-top"
                                         alt="Backyard concrete patio surrounding a fire pit">
                                    <div class="card-body p-3">
                                        <h5 class="card-title mb-1">Backyard Entertaining Space</h5>
                                        <p class="card-text small text-muted mb-0">Perfect for gatherings &amp; BBQs.</p>
                                    </div>
                                </div>
                            </div>

                            {{-- 7 --}}
                            <div class="portfolio-slide">
                                <div class="card border-0 shadow-sm h-100">
                                    <img src="{{ asset('storage/portfolio/final/img-7.jpg') }}"
                                         class="card-img-top"
                                         alt="Backyard concrete patio surrounding a fire pit">
                                    <div class="card-body p-3">
                                        <h5 class="card-title mb-1">Backyard Entertaining Space</h5>
                                        <p class="card-text small text-muted mb-0">Perfect for gatherings &amp; BBQs.</p>
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

        <!-- Portfolio Lightbox Modal -->
        <div class="modal fade" id="portfolioModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content bg-dark text-white border-0">
                    <div class="modal-body p-0 position-relative">

                        <!-- Close button -->
                        <button type="button"
                                class="close text-white position-absolute portfolio-close"
                                style="top: 10px; right: 20px; z-index: 20;"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>

                        <!-- Prev / Next arrows -->
                        <button type="button"
                                class="portfolio-nav portfolio-prev"
                                aria-label="Previous image">
                            &#10094;
                        </button>

                        <button type="button"
                                class="portfolio-nav portfolio-next"
                                aria-label="Next image">
                            &#10095;
                        </button>

                        <!-- Main image -->
                        <img id="portfolioModalImage"
                             src=""
                             alt=""
                             class="img-fluid w-100 d-block">

                        <!-- Caption area -->
                        <div class="p-3">
                            <h5 id="portfolioModalTitle" class="mb-1"></h5>
                            <p id="portfolioModalCaption" class="small mb-0 text-muted"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Portfolio Lightbox Modal -->


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
                    function initPortfolioLightbox() {
                        // All clickable cards: masonry + slider
                        var cardNodes = document.querySelectorAll(
                            '#portfolio .masonry-item .card, ' +
                            '#portfolioSlider .portfolio-slide .card'
                        );
                        if (!cardNodes.length) return;

                        var slides = [];
                        var currentIndex = 0;

                        // Modal elements
                        var modalEl      = document.getElementById('portfolioModal');
                        var modalImage   = document.getElementById('portfolioModalImage');
                        var modalTitle   = document.getElementById('portfolioModalTitle');
                        var modalCaption = document.getElementById('portfolioModalCaption');
                        var prevBtn      = modalEl ? modalEl.querySelector('.portfolio-prev') : null;
                        var nextBtn      = modalEl ? modalEl.querySelector('.portfolio-next') : null;
                        var closeBtn     = modalEl ? modalEl.querySelector('.portfolio-close') : null;

                        // Build slides array & attach click handlers
                        cardNodes.forEach(function (card, index) {
                            var img = card.querySelector('img.card-img-top');
                            if (!img) return;

                            var titleEl   = card.querySelector('.card-title');
                            var captionEl = card.querySelector('.card-text');

                            slides.push({
                                src: img.getAttribute('src'),
                                alt: img.getAttribute('alt') || '',
                                title: titleEl ? titleEl.textContent.trim() : '',
                                caption: captionEl ? captionEl.textContent.trim() : ''
                            });

                            // Make whole card clickable
                            card.style.cursor = 'pointer';
                            card.dataset.index = index;

                            card.addEventListener('click', function () {
                                var idx = parseInt(this.dataset.index, 10);
                                openModal(idx);
                            });
                        });

                        function showSlide(index) {
                            if (!slides.length || !modalEl) return;

                            // Wrap index (first/last)
                            if (index < 0) {
                                index = slides.length - 1;
                            } else if (index >= slides.length) {
                                index = 0;
                            }

                            currentIndex = index;
                            var slide = slides[currentIndex];

                            modalImage.src = slide.src;
                            modalImage.alt = slide.alt;
                            modalTitle.textContent   = slide.title;
                            modalCaption.textContent = slide.caption || '';
                        }

                        function openModal(index) {
                            showSlide(index);

                            if (!modalEl) return;

                            // Bootstrap 5 style
                            if (window.bootstrap && bootstrap.Modal) {
                                var instance = bootstrap.Modal.getOrCreateInstance(modalEl);
                                instance.show();
                                return;
                            }

                            // Fallback if no Bootstrap modal JS
                            modalEl.classList.add('show');
                            modalEl.style.display = 'block';
                            modalEl.removeAttribute('aria-hidden');
                            document.body.classList.add('modal-open');
                        }

                        function closeModal() {
                            if (!modalEl) return;

                            // Bootstrap 5 style
                            if (window.bootstrap && bootstrap.Modal) {
                                var instance = bootstrap.Modal.getInstance(modalEl)
                                    || bootstrap.Modal.getOrCreateInstance(modalEl);
                                instance.hide();
                                return;
                            }

                            // Fallback
                            modalEl.classList.remove('show');
                            modalEl.style.display = 'none';
                            modalEl.setAttribute('aria-hidden', 'true');
                            document.body.classList.remove('modal-open');

                            // Remove any backdrops if they exist
                            var backs = document.querySelectorAll('.modal-backdrop');
                            backs.forEach(function (b) { b.parentNode.removeChild(b); });
                        }

                        // Prev / Next buttons
                        if (prevBtn) {
                            prevBtn.addEventListener('click', function () {
                                showSlide(currentIndex - 1);
                            });
                        }

                        if (nextBtn) {
                            nextBtn.addEventListener('click', function () {
                                showSlide(currentIndex + 1);
                            });
                        }

                        // Close button (the X)
                        if (closeBtn) {
                            closeBtn.addEventListener('click', function () {
                                closeModal();
                            });
                        }

                        // Keyboard navigation
                        document.addEventListener('keydown', function (e) {
                            if (!modalEl) return;
                            var isOpen = modalEl.classList.contains('show');
                            if (!isOpen) return;

                            if (e.key === 'ArrowLeft') {
                                showSlide(currentIndex - 1);
                            } else if (e.key === 'ArrowRight') {
                                showSlide(currentIndex + 1);
                            } else if (e.key === 'Escape') {
                                closeModal();
                            }
                        });
                    }

                    if (document.readyState === 'loading') {
                        document.addEventListener('DOMContentLoaded', initPortfolioLightbox);
                    } else {
                        initPortfolioLightbox();
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

        @endsection


</x-home-fullscreen-index>
