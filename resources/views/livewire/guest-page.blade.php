<div x-data="{ open: false }">
    <!-- Navbar & Hero Start -->
    <div class="container-fluid position-relative p-0">
        <nav class="navbar navbar-expand-lg navbar-light px-4 px-lg-5 py-3 py-lg-0">
            <a href="" class="navbar-brand p-0">
                <h1 class="text-primary m-0"><i class="fa fa-map-marker-alt me-3"></i>HANAPBOK</h1>
                <!-- <img src="img/logo.png" alt="Logo"> -->
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars"></span>
            </button>
            {{-- <div class="collapse navbar-collapse" id="navbarCollapse"> --}}
            <div class="navbar-nav ms-auto py-0">
                <a class="nav-item nav-link active">Home</a>
                <a href="{{ route('guest-booking') }}" class="nav-item nav-link">Book Now</a>
                @auth
                    {{-- <a href="{{ route('guest-booking') }}" class="nav-item nav-link">Book Now</a> --}}
                    <a href="{{ route('my-bookings') }}" class="nav-item nav-link">My Bookings</a>
                    <a href="{{ route('lost-items') }}" class="nav-item nav-link">Lost and Found Items</a>

                    {{-- <a class="nav-item nav-link position-relative">
                        <i class="fa fa-bell fs-5"></i>
                        <span class="position-absolute translate-middle badge rounded-pill bg-danger">
                            {{ $unreadNotificationsCount }}
                            <span class="visually-hidden">unread messages</span>
                        </span>
                    </a> --}}
                    <a @click="open = true" class="nav-item nav-link position-relative">
                        <i class="fa fa-bell fs-5"></i>
                        @if ($unreadNotificationsCount)
                            <span class="position-absolute translate-middle badge rounded-pill bg-danger">
                                {{ $unreadNotificationsCount }}
                                <span class="visually-hidden">unread messages</span>
                            </span>
                        @endif
                    </a>

                    <a href="{{ route('profile') }}" class="nav-item nav-link position-relative">
                        <i class="fa fa-user fs-5"></i>
                    </a>
                @endauth
            </div>

            @auth
                <!-- Overlay -->
                <div x-show="open" x-transition.opacity @click="open = false"
                    class="position-fixed top-0 start-0 w-100 h-200 bg-opacity-50" x-cloak>
                </div>

                <!-- Drawer -->
                <div x-show="open" x-transition:enter="transition transform duration-300"
                    x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
                    x-transition:leave="transition transform duration-300" x-transition:leave-start="translate-x-0"
                    x-transition:leave-end="-translate-x-full"
                    class="position-fixed top-0 start-0 bg-white h-100 shadow-lg border-end rounded-end"
                    style="width: 340px; z-index: 1050" x-cloak>
                    <!-- Header -->
                    <div class="px-4 py-3 border-bottom bg-light">
                        <!-- Header -->
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Notifications</h5>
                            <button class="btn btn-sm btn-light" @click="open = false" aria-label="Close">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2 mt-3">
                            <button wire:click="markAllAsRead" class="btn btn-sm btn-outline-success"
                                title="Mark all as read">
                                <i class="fa fa-check me-1"></i> Read All
                            </button>

                            <button wire:click="clearAll" class="btn btn-sm btn-outline-danger" wire:click="clearAll"
                                title="Clear all notifications">
                                <i class="fa fa-trash me-1"></i> Clear All
                            </button>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="p-4 overflow-auto" style="max-height: calc(100vh - 65px);">
                        @forelse($notifications as $notification)
                            @php
                                // $url = $notification->data['actions'][0]['url'] ?? '';

                                // $parentUrl = dirname($url);
                                // $bookingId = basename($parentUrl);
                            @endphp

                            <a class="text-decoration-none text-dark d-block">
                                <div class="d-flex align-items-start mb-3 p-3 bg-light rounded shadow-sm notification-item"
                                    style="transition: background-color 0.2s;">
                                    <div class="me-3">
                                        <i class="fa fa-bell text-primary fs-4"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold text-dark">
                                            {{ $notification->data['title'] ?? 'Notification' }}
                                        </div>
                                        <div class="small text-muted">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="text-center text-muted mt-4">
                                <i class="fa fa-check-circle fa-2x mb-2"></i>
                                <p>No new notifications.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endauth

            @auth
                <a href="" class="btn btn-primary rounded-pill py-2 px-4" wire:click.prevent="logout">Logout</a>
            @endauth

            @guest
                <a href="" class="btn btn-primary rounded-pill py-2 px-4" wire:click.prevent="login">Login</a>
            @endguest

            {{-- <a href="" class="btn btn-primary rounded-pill py-2 px-4" wire:click.prevent="logout">Logout</a> --}}
            {{-- </div> --}}
        </nav>

        <div class="container-fluid hero-header p-0">
            <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="{{ asset('carousel/carousel1.jpg') }}" class="d-block w-100" alt="Slide 1">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('carousel/carousel2.jpg') }}" class="d-block w-100" alt="Slide 2">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('carousel/carousel3.jpg') }}" class="d-block w-100" alt="Slide 3">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('carousel/carousel4.jpg') }}" class="d-block w-100" alt="Slide 2">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('carousel/carousel5.jpg') }}" class="d-block w-100" alt="Slide 3">
                    </div>
                </div>
            </div>

            <!-- Dark Overlay -->
            <div class="hero-overlay"></div>

            <!-- Hero Text Content -->
            <div class="hero-content container">
                <h1 class="display-3 text-white mb-3 animated slideInDown">Enjoy Your Vacation With Us</h1>
                <p class="fs-4 mb-4 text-white animated slideInDown">Escape the stress and routine of everyday life —
                    relax,
                    recharge, and make unforgettable memories.</p>
            </div>
        </div>
    </div>
    <!-- Navbar & Hero End -->


    <!-- About Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s" style="min-height: 400px;">
                    <div class="position-relative h-100">
                        <img class="img-fluid position-absolute w-100 h-100"
                            src="{{ asset('img/homepaeabouthanapbok.jpg') }}" alt=""
                            style="object-fit: cover;">
                    </div>
                </div>
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.3s">
                    <h6 class="section-title bg-white text-start text-primary pe-3">About Us</h6>
                    <h1 class="mb-4">Welcome to <span class="text-primary">HANAPBOK</span></h1>
                    <p class="mb-4">We understand how important it is to find the perfect place to relax, unwind,
                        and create lasting memories. That’s why HanapBok was created — to make the process of
                        finding and reserving resorts simple, fast, and stress-free. Whether you're planning a
                        weekend getaway, a family vacation, or a special celebration, we’re here to help you connect
                        with the best spots Mabini has to offer.</p>
                    <p class="mb-4">With an easy-to-use interface, verified listings, and real-time availability,
                        HanapBok allows you to browse, compare, and book your ideal resort — all in one place. No
                        more guessing or time-consuming calls. Just pure convenience at your fingertips.</p>
                    <div class="row gy-2 gx-4 mb-4">
                        <div class="col-sm-6">
                            <p class="mb-0"><i class="fa fa-arrow-right text-primary me-2"></i>24/7 Service</p>
                        </div>
                        <div class="col-sm-6">
                            <p class="mb-0"><i class="fa fa-arrow-right text-primary me-2"></i>Good
                                Accommodations</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->

    {{-- about mabini --}}
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s" style="min-height: 400px;">
                    <div class="position-relative h-100">
                        <img class="img-fluid position-absolute w-100 h-100"
                            src="{{ asset('img/homepageaboutmabini.jpg') }}" alt=""
                            style="object-fit: cover;">
                    </div>
                </div>
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.3s">
                    <h6 class="section-title bg-white text-start text-primary pe-3">About Mabini</h6>
                    {{-- <h1 class="mb-4">Welcome to <span class="text-primary">HANAPBOK</span></h1> --}}
                    <p class="mb-4">The Municipality of Mabini, located in Davao de Oro in the Davao Region of
                        Mindanao, Philippines, was officially established on May 28, 1953. Originally named Doña Alicia
                        in honor of Alicia Syquia, the wife of President Elpidio Quirino, the town was later renamed
                        Mabini in 1954 to pay tribute to the Filipino revolutionary hero Apolinario Mabini. The
                        municipality is led by Mayor Emerson L. Luego.</p>
                    <p class="mb-4">The beach resorts in Mabini are located across four barangays, including San
                        Antonio, Del Pilar, Pindasan, and Tagnanan. All resorts receive a total of approximately 180,000
                        customers or tourists per month.</p>
                    {{-- <div class="row gy-2 gx-4 mb-4">
                        <div class="col-sm-6">
                            <p class="mb-0"><i class="fa fa-arrow-right text-primary me-2"></i>24/7 Service</p>
                        </div>
                        <div class="col-sm-6">
                            <p class="mb-0"><i class="fa fa-arrow-right text-primary me-2"></i>Good
                                Accommodations</p>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>

    <!-- Service Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h6 class="section-title bg-white text-center text-primary px-3">Services</h6>
                <h1 class="mb-5">LOST AND FOUND ITEM TRACKING & RESORT MAP</h1>
            </div>
            <div class="row g-4">
                <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="service-item rounded pt-3">
                        <div class="p-4">
                            <i class="fa fa-3x fa-globe text-primary mb-4"></i>
                            <h5>Resort Reservation</h5>
                            <p>Booking affordable resorts online</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="service-item rounded pt-3">
                        <div class="p-4">
                            <i class="fa fa-3x fa-globe text-primary mb-4"></i>
                            <h5>Tracking Lost Items</h5>
                            <p>Track lost items</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="service-item rounded pt-3">
                        <div class="p-4">
                            <i class="fa fa-3x fa-globe text-primary mb-4"></i>
                            <h5>GPS</h5>
                            <p>Locate Resorts via GPS</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Service End -->


    <!-- Destination Start -->
    <div class="container-xxl py-5 destination">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h6 class="section-title bg-white text-center text-primary px-3">RESORTS</h6>
                <h1 class="mb-5">Popular Resorts</h1>
            </div>
            <div class="row g-3">
                <div class="col-lg-7 col-md-6">
                    <div class="row g-3">
                        <div class="col-lg-12 col-md-12 wow zoomIn" data-wow-delay="0.1s">
                            <a class="position-relative d-block overflow-hidden" href="">
                                <img class="img-fluid" src="img/beachview.JPG" alt="" style="width: 100%;">
                                {{-- <div
                                        class="bg-white text-danger fw-bold position-absolute top-0 start-0 m-3 py-1 px-2">
                                        30% OFF</div> --}}
                                <div
                                    class="bg-white text-primary fw-bold position-absolute bottom-0 end-0 m-3 py-1 px-2">
                                    Beach View Resort</div>
                            </a>
                        </div>
                        <div class="col-lg-6 col-md-12 wow zoomIn" data-wow-delay="0.3s">
                            <a class="position-relative d-block overflow-hidden" href="">
                                <img class="img-fluid" src="img/kaza.jpg" alt="" style="height: 90%;">
                                {{-- <div
                                        class="bg-white text-danger fw-bold position-absolute top-0 start-0 m-3 py-1 px-2">
                                        25% OFF</div> --}}
                                <div
                                    class="bg-white text-primary fw-bold position-absolute bottom-0 end-0 m-3 py-1 px-2">
                                    Khaza De Miranda</div>
                            </a>
                        </div>
                        <div class="col-lg-6 col-md-12 wow zoomIn" data-wow-delay="0.5s">
                            <a class="position-relative d-block overflow-hidden" href="">
                                <img class="img-fluid" src="img/dusit.jpg" alt=""
                                    style="height: 310px;width: 100%;">
                                {{-- <div
                                        class="bg-white text-danger fw-bold position-absolute top-0 start-0 m-3 py-1 px-2">
                                        35% OFF</div> --}}
                                <div
                                    class="bg-white text-primary fw-bold position-absolute bottom-0 end-0 m-3 py-1 px-2">
                                    Dusit Thani Lubi Plantation Resort</div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-md-6 wow zoomIn" data-wow-delay="0.7s" style="min-height: 350px;">
                    <a class="position-relative d-block h-100 overflow-hidden" href="">
                        <img class="img-fluid position-absolute w-100 h-100" src="img/sea.jpg" alt=""
                            style="object-fit: cover;">
                        {{-- <div class="bg-white text-danger fw-bold position-absolute top-0 start-0 m-3 py-1 px-2">20%
                                OFF</div> --}}
                        <div class="bg-white text-primary fw-bold position-absolute bottom-0 end-0 m-3 py-1 px-2">
                            Sea Eagle Beach Resort</div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Destination Start -->

    <!-- Process Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center pb-4 wow fadeInUp" data-wow-delay="0.1s">
                <h6 class="section-title bg-white text-center text-primary px-3">Process</h6>
                <h1 class="mb-5">3 Easy Steps</h1>
            </div>
            <div class="row gy-5 gx-4 justify-content-center">
                <div class="col-lg-4 col-sm-6 text-center pt-4 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="position-relative border border-primary pt-5 pb-4 px-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-primary rounded-circle position-absolute top-0 start-50 translate-middle shadow"
                            style="width: 100px; height: 100px;">
                            <i class="fa fa-globe fa-3x text-white"></i>
                        </div>
                        <h5 class="mt-4">Choose A Resort</h5>
                        <hr class="w-25 mx-auto bg-primary mb-1">
                        <hr class="w-50 mx-auto bg-primary mt-0">
                        <p class="mb-0">
                            Discover your perfect getaway—choose a resort in Mabini, Davao Oriental for relaxing
                            beachfront stays and stunning ocean views.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6 text-center pt-4 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="position-relative border border-primary pt-5 pb-4 px-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-primary rounded-circle position-absolute top-0 start-50 translate-middle shadow"
                            style="width: 100px; height: 100px;">
                            <i class="fa fa-dollar-sign fa-3x text-white"></i>
                        </div>
                        <h5 class="mt-4">Pay Online</h5>
                        <hr class="w-25 mx-auto bg-primary mb-1">
                        <hr class="w-50 mx-auto bg-primary mt-0">
                        <p class="mb-0">
                            Enjoy hassle-free reservations with our secure and convenient online payment options,
                            including GCash for fast and easy transactions.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6 text-center pt-4 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="position-relative border border-primary pt-5 pb-4 px-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-primary rounded-circle position-absolute top-0 start-50 translate-middle shadow"
                            style="width: 100px; height: 100px;">
                            <i class="fa fa-plane fa-3x text-white"></i>
                        </div>
                        <h5 class="mt-4">Enjoy Today</h5>
                        <hr class="w-25 mx-auto bg-primary mb-1">
                        <hr class="w-50 mx-auto bg-primary mt-0">
                        <p class="mb-0">
                            Book your resort now and take the first step toward your dream escape—your tropical
                            adventure is just a flight away.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Process Start -->


    {{-- gps --}}
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h6 class="section-title bg-white text-center text-primary px-3">Location</h6>
                <h1 class="mb-5">SEE MAP OR FIND LOCATION</h1>
            </div>
            <div class="row g-4">
                {{-- <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <h5>Get In Touch</h5>
                    <p class="mb-4">Tempor erat elitr rebum at clita. Diam dolor diam ipsum sit. Aliqu diam amet diam
                        et eos</p>
                    <div class="d-flex align-items-center mb-4">
                        <div class="d-flex align-items-center justify-content-center flex-shrink-0 bg-primary"
                            style="width: 50px; height: 50px;">
                            <i class="fa fa-map-marker-alt text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h5 class="text-primary">Office</h5>
                            <p class="mb-0">123 Street, New York, USA</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-4">
                        <div class="d-flex align-items-center justify-content-center flex-shrink-0 bg-primary"
                            style="width: 50px; height: 50px;">
                            <i class="fa fa-phone-alt text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h5 class="text-primary">Mobile</h5>
                            <p class="mb-0">+012 345 67890</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="d-flex align-items-center justify-content-center flex-shrink-0 bg-primary"
                            style="width: 50px; height: 50px;">
                            <i class="fa fa-envelope-open text-white"></i>
                        </div>
                        <div class="ms-3">
                            <h5 class="text-primary">Email</h5>
                            <p class="mb-0">info@example.com</p>
                        </div>
                    </div>
                </div> --}}
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                    {{-- <iframe class="position-relative rounded w-100 h-100"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3001156.4288297426!2d-78.01371936852176!3d42.72876761954724!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4ccc4bf0f123a5a9%3A0xddcfc6c1de189567!2sNew%20York%2C%20USA!5e0!3m2!1sen!2sbd!4v1603794290143!5m2!1sen!2sbd"
                        frameborder="0" style="min-height: 300px; border:0;" allowfullscreen="" aria-hidden="false"
                        tabindex="0"></iframe> --}}
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1512.290641204875!2d125.85672823302018!3d7.280530837458852!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x32f95989f485358d%3A0xa763bdfd4defd8d2!2sMabini%20Davao%20de%20Oro!5e1!3m2!1sen!2sph!4v1752594915219!5m2!1sen!2sph"
                        width="1400" height="600" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
                {{-- <div class="col-lg-4 col-md-12 wow fadeInUp" data-wow-delay="0.5s">
                    <form>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="name"
                                        placeholder="Your Name">
                                    <label for="name">Your Name</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="email"
                                        placeholder="Your Email">
                                    <label for="email">Your Email</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="subject" placeholder="Subject">
                                    <label for="subject">Subject</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control" placeholder="Leave a message here" id="message" style="height: 100px"></textarea>
                                    <label for="message">Message</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary w-100 py-3" type="submit">Send Message</button>
                            </div>
                        </div>
                    </form>
                </div> --}}
            </div>
        </div>
    </div>


    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-light footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                {{-- <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-3">Company</h4>
                    <a class="btn btn-link" href="">About Us</a>
                    <a class="btn btn-link" href="">Contact Us</a>
                    <a class="btn btn-link" href="">Privacy Policy</a>
                    <a class="btn btn-link" href="">Terms & Condition</a>
                    <a class="btn btn-link" href="">FAQs & Help</a>
                </div> --}}
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-3">Contact</h4>
                    <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>Anilao Port Anilao Proper , Mabini ,
                        Davao De Oro , Philippines</p>
                    <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>0969 643 3420</p>
                    <p class="mb-2"><i class="fa fa-envelope me-3"></i>icmomabini@gmail.com</p>
                    <p class="mb-2"><i class="fab fa-facebook me-3"></i>Mabini ICM Tourism Office</p>
                    {{-- <div class="d-flex pt-2">
                        <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-twitter"></i></a>
                        <a class="btn btn-outline-light btn-social" href=""><i
                                class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-outline-light btn-social" href=""><i class="fab fa-youtube"></i></a>
                        <a class="btn btn-outline-light btn-social" href=""><i
                                class="fab fa-linkedin-in"></i></a>
                    </div> --}}
                </div>
                {{-- <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-3">Gallery</h4>
                    <div class="row g-2 pt-2">
                        <div class="col-4">
                            <img class="img-fluid bg-light p-1" src="img/package-1.jpg" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid bg-light p-1" src="img/package-2.jpg" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid bg-light p-1" src="img/package-3.jpg" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid bg-light p-1" src="img/package-2.jpg" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid bg-light p-1" src="img/package-3.jpg" alt="">
                        </div>
                        <div class="col-4">
                            <img class="img-fluid bg-light p-1" src="img/package-1.jpg" alt="">
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-3">Newsletter</h4>
                    <p>Dolor amet sit justo amet elitr clita ipsum elitr est.</p>
                    <div class="position-relative mx-auto" style="max-width: 400px;">
                        <input class="form-control border-primary w-100 py-3 ps-4 pe-5" type="text"
                            placeholder="Your email">
                        <button type="button"
                            class="btn btn-primary py-2 position-absolute top-0 end-0 mt-2 me-2">SignUp</button>
                    </div>
                </div> --}}
            </div>
        </div>
        <div class="container">
            <div class="copyright">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        &copy; <a class="border-bottom" href="#">HANAPBOK</a>, All Right Reserved.
                        {{-- Designed By <a class="border-bottom" href="https://htmlcodex.com">HTML Codex</a> --}}
                    </div>
                    {{-- <div class="col-md-6 text-center text-md-end">
                        <div class="footer-menu">
                            <a href="">Home</a>
                            <a href="">Cookies</a>
                            <a href="">Help</a>
                            <a href="">FQAs</a>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
</div>
