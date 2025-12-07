<x-home-fullscreen-index>
    @section('page-title') Contact Us | {{$settings->app_name}} @endsection
    @section('description') Lakai Concrete @endsection
    @section('background-image')
        {{ asset('storage/' . (($settings->internal_background ?? null) ?: 'app-images/interior-banner-1.jpg')) }}
    @endsection
    @section('banner')
{{--        <img src="{{asset('storage/app-images/interior-banner.jpg')}}" class="img-responsive" alt="MEX Learning Banner" title="MEX Learning Banner"/>--}}
    @endsection

    @section('style')

        <style>


        </style>

    @endsection

    @section('content')

    <!-- Title -->
    <div class="white-back mt-4">
        <div class="row">
            <div class="col-sm-6">
                <h1 class="text-primary mb-0">Contact Us</h1>
            </div>

        </div>
    </div>
    <!-- /Title -->

    <!-- Contact -->
    <div id="portfolio" class="white-back mt-4">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12 text-center">
                    <h2 class="fw-bold text-black">Contact Us Anytime!</h2>
                    <p class="text-muted mb-0">
                        If you have any questions or concerns, please feel free to contact us. We are here to help!
                    </p>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-sm-6">
                    <h3 class="fw-bold">Give us a Call</h3>
                    <p class="text-muted mb-0">
                        <a href="tel:{{$settings->company_phone}}" class="btn btn-secondary visible-xs">{{$settings->company_phone}}</a>
                        <a href="tel:{{$settings->company_phone}}" class="text-decoration-none text-primary hidden-xs">{{$settings->company_phone}}</a>
                    </p>

                    <hr>

                    <h3 class="fw-bold">Or Send Us an Email</h3>
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
                <div class="col-sm-6">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d178840.21267632963!2d-122.65438555!3d45.5427145!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x54950b0b7da97427%3A0x1c36b9e6f6d18591!2sPortland%2C%20OR!5e0!3m2!1sen!2sus!4v1765097853023!5m2!1sen!2sus" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact -->
    @endsection

    @section('scripts')

        <script>
            $(document).ready(function() {

                $("input[name='phone']").keyup(function() {
                    $(this).val($(this).val().replace(/^(\d{3})(\d{3})(\d+)$/, "$1-$2-$3"));
                });

            });
        </script>

    @endsection

</x-home-fullscreen-index>
