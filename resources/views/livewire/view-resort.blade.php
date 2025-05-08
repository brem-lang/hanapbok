<div>

    <body>
        <!-- Spinner Start -->
        {{-- <div id="spinner"
            class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div> --}}
        <!-- Spinner End -->

        <!-- Navbar & Hero Start -->
        <div class="container-fluid position-relative p-0">
            <nav class="navbar navbar-expand-lg navbar-light px-4 px-lg-5 py-3 py-lg-0">
                <a href="" class="navbar-brand p-0">
                    <h1 class="text-primary m-0"><i class="fa fa-map-marker-alt me-3"></i>HANAPBOK</h1>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars"></span>
                </button>
                <div class="navbar-nav ms-auto py-0">
                    <a href="{{ route('index') }}" class="nav-item nav-link">Home</a>
                    <a class="nav-item nav-link active">Book Now</a>
                </div>
                <a href="" class="btn btn-primary rounded-pill py-2 px-4" wire:click.prevent="logout">Logout</a>
            </nav>

            <div class="container-fluid bg-primary py-5 mb-5 hero-header">
                <div class="container py-5">
                    <div class="row justify-content-center py-5">
                        <div class="col-lg-10 pt-lg-5 mt-lg-5 text-center">
                            <h1 class="display-3 text-white animated slideInDown">{{ $record->name }}</h1>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb justify-content-center">
                                    <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('guest-booking') }}">Resorts</a></li>
                                    <li class="breadcrumb-item text-white active" aria-current="page">
                                        {{ $record->name }}</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Navbar & Hero End -->


        @if ($this->activePage == 'view')
            <!-- About Start -->
            <div class="container-xxl py-5">
                <div class="container">
                    <div class="row g-5">
                        <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s" style="min-height: 400px;">
                            <div class="position-relative h-100">
                                <img class="img-fluid position-absolute w-100 h-100"
                                    src="{{ $record->image ? asset('resorts-photo/' . $record->image) : asset('img/about.jpg') }}"
                                    alt="" style="object-fit: cover;">
                            </div>
                        </div>
                        <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.3s">
                            <h6 class="section-title bg-white text-start text-primary pe-3">INFORMATION</h6>
                            <p class="mb-2" style="font-size: 16px;">{{ $record->description }}</p>
                            <h6 class="section-title bg-white text-start text-primary pe-3">Other Details</h6>
                            <div class="row gy-2 gx-4 mb-2">
                                @foreach ($record?->others ?? [] as $other)
                                    <div class="col-sm-6">
                                        <p class="mb-0" style="font-size: 13px;"><i
                                                class="fa fa-arrow-right text-primary me-2"></i>{{ $other['name'] }}</p>
                                    </div>
                                @endforeach
                            </div>
                            <h6 class="section-title bg-white text-start text-primary pe-3">Entrance Fees</h6>
                            <div class="row gy-2 gx-4 mb-2">
                                @foreach ($record?->entranceFees ?? [] as $entranceFee)
                                    <div class="col-sm-6">
                                        <p class="mb-0" style="font-size: 13px;"><i
                                                class="fa fa-arrow-right text-primary me-2"></i>
                                            {{ $entranceFee['name'] }} -
                                            {{ $entranceFee['type'] === 'night_tour' ? 'Night Tour' : ($entranceFee['type'] === 'day_tour' ? 'Day Tour' : 'Free') }}
                                            -
                                            ₱{{ $entranceFee['price'] }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                            <a href="" class="btn btn-primary py-1 px-3 mt-2" wire:click.prevent="book">Book
                                Now</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- About End -->

            <!-- Package Start -->
            <div class="container-xxl py-5">
                <div class="container">
                    <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                        <h6 class="section-title bg-white text-center text-primary px-3">ACCOMODATIONS</h6>
                        <h1 class="mb-5">Rooms & Cottages</h1>
                    </div>
                    <div class="row g-4 justify-content-center">
                        {{-- <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="package-item">
                            <div class="overflow-hidden">
                                <img class="img-fluid" src="{{ asset('img/package-1.jpg') }}" alt="">
                            </div>
                            <div class="d-flex border-bottom">
                                <small class="flex-fill text-center border-end py-2"><i
                                        class="fa fa-map-marker-alt text-primary me-2"></i>Thailand</small>
                                <small class="flex-fill text-center border-end py-2"><i
                                        class="fa fa-calendar-alt text-primary me-2"></i>3 days</small>
                                <small class="flex-fill text-center py-2"><i class="fa fa-user text-primary me-2"></i>2
                                    Person</small>
                            </div>
                            <div class="text-center p-4">
                                <h3 class="mb-0">$149.00</h3>
                                <div class="mb-3">
                                    <small class="fa fa-star text-primary"></small>
                                    <small class="fa fa-star text-primary"></small>
                                    <small class="fa fa-star text-primary"></small>
                                    <small class="fa fa-star text-primary"></small>
                                    <small class="fa fa-star text-primary"></small>
                                </div>
                                <p>Tempor erat elitr rebum at clita. Diam dolor diam ipsum sit diam amet diam eos</p>
                                <div class="d-flex justify-content-center mb-2">
                                    <a href="#" class="btn btn-sm btn-primary px-3 border-end"
                                        style="border-radius: 30px 0 0 30px;">Read More</a>
                                    <a href="#" class="btn btn-sm btn-primary px-3"
                                        style="border-radius: 0 30px 30px 0;">Book Now</a>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                        {{-- <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                        <div class="package-item">
                            <div class="overflow-hidden">
                                <img class="img-fluid" src="img/package-2.jpg" alt="">
                            </div>
                            <div class="d-flex border-bottom">
                                <small class="flex-fill text-center border-end py-2"><i
                                        class="fa fa-map-marker-alt text-primary me-2"></i>Indonesia</small>
                                <small class="flex-fill text-center border-end py-2"><i
                                        class="fa fa-calendar-alt text-primary me-2"></i>3 days</small>
                                <small class="flex-fill text-center py-2"><i
                                        class="fa fa-user text-primary me-2"></i>2
                                    Person</small>
                            </div>
                            <div class="text-center p-4">
                                <h3 class="mb-0">$139.00</h3>
                                <div class="mb-3">
                                    <small class="fa fa-star text-primary"></small>
                                    <small class="fa fa-star text-primary"></small>
                                    <small class="fa fa-star text-primary"></small>
                                    <small class="fa fa-star text-primary"></small>
                                    <small class="fa fa-star text-primary"></small>
                                </div>
                                <p>Tempor erat elitr rebum at clita. Diam dolor diam ipsum sit diam amet diam eos</p>
                                <div class="d-flex justify-content-center mb-2">
                                    <a href="#" class="btn btn-sm btn-primary px-3 border-end"
                                        style="border-radius: 30px 0 0 30px;">Read More</a>
                                    <a href="#" class="btn btn-sm btn-primary px-3"
                                        style="border-radius: 0 30px 30px 0;">Book Now</a>
                                </div>
                            </div>
                        </div>
                    </div> --}}

                        @foreach ($record->items ?? [] as $item)
                            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                                <div class="package-item">
                                    {{-- <div class="overflow-hidden">
                                    <img class="img-fluid" src="img/package-3.jpg" alt="">
                                </div>
                                <div class="d-flex border-bottom">
                                    <small class="flex-fill text-center border-end py-2"><i
                                            class="fa fa-map-marker-alt text-primary me-2"></i>Malaysia</small>
                                    <small class="flex-fill text-center border-end py-2"><i
                                            class="fa fa-calendar-alt text-primary me-2"></i>3 days</small>
                                    <small class="flex-fill text-center py-2"><i
                                            class="fa fa-user text-primary me-2"></i>2
                                        Person</small>
                                </div> --}}
                                    <div class="text-center p-4">
                                        <h3 class="mb-0">₱ {{ $item['price'] }}</h3>
                                        {{-- <div class="mb-3">
                                        <small class="fa fa-star text-primary"></small>
                                        <small class="fa fa-star text-primary"></small>
                                        <small class="fa fa-star text-primary"></small>
                                        <small class="fa fa-star text-primary"></small>
                                        <small class="fa fa-star text-primary"></small>
                                    </div> --}}
                                        <p>{{ $item['name'] }}</p>
                                        <p style="font-size: 12px;">
                                            {{ isset($item['type']) ? ($item['type'] == 'day_tour' ? 'Day Tour' : 'Night Tour') : '---' }}
                                        </p>
                                        {{-- <div class="d-flex justify-content-center mb-2">
                                        <a href="#" class="btn btn-sm btn-primary px-3 border-end"
                                            style="border-radius: 30px 0 0 30px;">Read More</a>
                                        <a href="#" class="btn btn-sm btn-primary px-3"
                                            style="border-radius: 0 30px 30px 0;">Book Now</a>
                                    </div> --}}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- Package End -->
        @endif

        {{-- @if ($this->activePage == 'validation')
            <div class="container-xxl py-5">
                <div class="container">
                    <livewire:validate-page />
                </div>
            </div>
        @endif --}}
        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>


        <!-- JavaScript Libraries -->
        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

        <script src="{{ asset('lib/wow/wow.min.js') }}"></script>
        <script src="{{ asset('lib/easing/easing.min.js') }}"></script>
        <script src="{{ asset('lib/waypoints/waypoints.min.js') }}"></script>
        <script src="{{ asset('lib/owlcarousel/owl.carousel.min.js') }}"></script>
        <script src="{{ asset('lib/tempusdominus/js/moment.min.js') }}"></script>
        <script src="{{ asset('lib/tempusdominus/js/moment-timezone.min.js') }}"></script>
        <script src="{{ asset('lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js') }}"></script>

        <!-- Template Javascript -->
        <script src="{{ asset('js/guest/js/main.js') }}"></script>

        {{-- links --}}
        <link href="{{ asset('css/guest/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/guest/style.css') }}" rel="stylesheet">
        <link href="{{ asset('lib/animate/animate.min.css') }}" rel="stylesheet">
        <link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
        <link href="{{ asset('lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css') }}" rel="stylesheet">

        <!-- Google Web Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Nunito:wght@600;700;800&display=swap"
            rel="stylesheet">

        <!-- Icon Font Stylesheet -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    </body>
</div>
