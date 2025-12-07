<x-home-fullscreen-index>
    @section('page-title') Portfolio | {{$settings->app_name}} @endsection
    @section('description') Lakai Concrete @endsection
    @section('background-image')
        {{ asset('storage/' . (($settings->internal_background ?? null) ?: 'app-images/interior-banner-1.jpg')) }}
    @endsection
    @section('banner')
{{--        <img src="{{asset('storage/app-images/interior-banner.jpg')}}" class="img-responsive" alt="MEX Learning Banner" title="MEX Learning Banner"/>--}}
    @endsection

    @section('style')

            <style>
                .masonry-grid {
                    column-count: 1;
                    column-gap: 1.25rem;
                }

                @media (min-width: 576px) {
                    .masonry-grid {
                        column-count: 2;
                    }
                }

                @media (min-width: 992px) {
                    .masonry-grid {
                        column-count: 3;
                    }
                }

                @media (min-width: 1200px) {
                    .masonry-grid {
                        column-count: 4;
                    }
                }

                .masonry-item {
                    break-inside: avoid;
                    margin-bottom: 1.25rem;
                }

                .portfolio-nav {
                    position: fixed;                /* relative to viewport */
                    top: 50%;                       /* vertically centered */
                    transform: translateY(-50%);
                    border: none;
                    background: rgba(0, 0, 0, 0.45);
                    color: #fff;
                    font-size: 2.5rem;
                    line-height: 1;
                    padding: 0.25rem 0.75rem;
                    cursor: pointer;
                    z-index: 1060;                  /* above modal content */
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

        <!-- Title -->
        <div class="white-back mt-4">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="text-primary mb-0">Portfolio</h1>
                </div>

            </div>
        </div>
        <!-- /Title -->

        <!-- Portfolio -->
        <div id="portfolio" class="white-back mt-4">
            <div class="container">
                <div class="row mb-4">
                    <div class="col-12 text-center">
                        <h2 class="fw-bold text-black">Recent Concrete Projects</h2>
                        <p class="text-muted mb-0">
                            Driveways, patios, walkways &amp; custom flatwork for residential homes.
                        </p>
                    </div>
                </div>

                <div class="masonry-grid">
                    <!-- 1 -->
                    <div class="masonry-item">
                        <div class="card border-0 shadow-sm">
                            <img src="{{asset('storage/portfolio/temp-1.jpg')}}"
                                 class="card-img-top"
                                 alt="Freshly poured concrete driveway in front of a home">
                            <div class="card-body p-3">
                                <h5 class="card-title mb-1">New Concrete Driveway</h5>
                                <p class="card-text small text-muted mb-0">Clean, durable finish for everyday use.</p>
                            </div>
                        </div>
                    </div>

                    <!-- 2 -->
                    <div class="masonry-item">
                        <div class="card border-0 shadow-sm">
                            <img src="{{asset('storage/portfolio/temp-2.jpg')}}"
                                 class="card-img-top"
                                 alt="Stamped concrete patio with outdoor furniture">
                            <div class="card-body p-3">
                                <h5 class="card-title mb-1">Stamped Concrete Patio</h5>
                                <p class="card-text small text-muted mb-0">Stamped pattern to mimic stone.</p>
                            </div>
                        </div>
                    </div>

                    <!-- 3 -->
                    <div class="masonry-item">
                        <div class="card border-0 shadow-sm">
                            <img src="{{asset('storage/portfolio/temp-3.jpg')}}"
                                 class="card-img-top"
                                 alt="Concrete walkway leading to a front door">
                            <div class="card-body p-3">
                                <h5 class="card-title mb-1">Front Walkway</h5>
                                <p class="card-text small text-muted mb-0">Smooth finish with crisp edges.</p>
                            </div>
                        </div>
                    </div>

                    <!-- 4 -->
                    <div class="masonry-item">
                        <div class="card border-0 shadow-sm">
                            <img src="{{asset('storage/portfolio/temp-4.jpg')}}"
                                 class="card-img-top"
                                 alt="Concrete stairs and entry landing">
                            <div class="card-body p-3">
                                <h5 class="card-title mb-1">Entry Stairs &amp; Landing</h5>
                                <p class="card-text small text-muted mb-0">Safe, level steps to the front door.</p>
                            </div>
                        </div>
                    </div>

                    <!-- 5 -->
                    <div class="masonry-item">
                        <div class="card border-0 shadow-sm">
                            <img src="{{asset('storage/portfolio/temp-5.jpg')}}"
                                 class="card-img-top"
                                 alt="Backyard concrete patio surrounding a fire pit">
                            <div class="card-body p-3">
                                <h5 class="card-title mb-1">Backyard Entertaining Space</h5>
                                <p class="card-text small text-muted mb-0">Perfect for gatherings &amp; BBQs.</p>
                            </div>
                        </div>
                    </div>

                    <!-- 8 -->
                    <div class="masonry-item">
                        <div class="card border-0 shadow-sm">
                            <img src="{{asset('storage/portfolio/temp-8.jpg')}}"
                                 class="card-img-top"
                                 alt="Concrete garden path next to landscaping">
                            <div class="card-body p-3">
                                <h5 class="card-title mb-1">Garden Path</h5>
                                <p class="card-text small text-muted mb-0">Clean lines tying into landscape.</p>
                            </div>
                        </div>
                    </div>

                    <!-- 9 -->
                    <div class="masonry-item">
                        <div class="card border-0 shadow-sm">
                            <img src="{{asset('storage/portfolio/temp-9.jpg')}}"
                                 class="card-img-top"
                                 alt="Concrete slab prepared for a shed or small structure">
                            <div class="card-body p-3">
                                <h5 class="card-title mb-1">Shed Slab</h5>
                                <p class="card-text small text-muted mb-0">Level slab ready for a new structure.</p>
                            </div>
                        </div>
                    </div>


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
                (function () {
                    function initPortfolioLightbox() {
                        // Grab all portfolio cards
                        var itemNodes = document.querySelectorAll('#portfolio .masonry-item');
                        if (!itemNodes.length) return;

                        var slides = [];
                        var currentIndex = 0;

                        // Modal elements
                        var modalEl      = document.getElementById('portfolioModal');
                        var modalImage   = document.getElementById('portfolioModalImage');
                        var modalTitle   = document.getElementById('portfolioModalTitle');
                        var modalCaption = document.getElementById('portfolioModalCaption');
                        var prevBtn      = modalEl ? modalEl.querySelector('.portfolio-prev') : null;
                        var nextBtn      = modalEl ? modalEl.querySelector('.portfolio-next') : null;

                        // Build slides array & attach click handlers
                        itemNodes.forEach(function (item, index) {
                            var img = item.querySelector('img.card-img-top');
                            if (!img) return;

                            var titleEl   = item.querySelector('.card-title');
                            var captionEl = item.querySelector('.card-text');

                            slides.push({
                                src: img.getAttribute('src'),
                                alt: img.getAttribute('alt') || '',
                                title: titleEl ? titleEl.textContent.trim() : '',
                                caption: captionEl ? captionEl.textContent.trim() : ''
                            });

                            // Make images clickable and remember index
                            img.style.cursor = 'pointer';
                            img.dataset.index = index;

                            img.addEventListener('click', function () {
                                var idx = parseInt(this.dataset.index, 10);
                                openModal(idx);
                            });
                        });

                        function showSlide(index) {
                            if (!slides.length || !modalEl) return;

                            // Wrap index
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

                            // Bootstrap 5 (no jQuery)
                            if (window.bootstrap && bootstrap.Modal) {
                                var instance = bootstrap.Modal.getOrCreateInstance(modalEl);
                                instance.show();
                                return;
                            }

                            // Fallback: basic manual show if Bootstrap JS not wired
                            modalEl.classList.add('show');
                            modalEl.style.display = 'block';
                            modalEl.removeAttribute('aria-hidden');
                            document.body.classList.add('modal-open');
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

                        // Optional: keyboard navigation (left/right arrows)
                        document.addEventListener('keydown', function (e) {
                            if (!modalEl) return;
                            var isOpen = modalEl.classList.contains('show');
                            if (!isOpen) return;

                            if (e.key === 'ArrowLeft') {
                                showSlide(currentIndex - 1);
                            } else if (e.key === 'ArrowRight') {
                                showSlide(currentIndex + 1);
                            }
                        });
                    }

                    // Run whether DOMContentLoaded already fired or not
                    if (document.readyState === 'loading') {
                        document.addEventListener('DOMContentLoaded', initPortfolioLightbox);
                    } else {
                        initPortfolioLightbox();
                    }
                })();
            </script>
        @endsection


</x-home-fullscreen-index>
