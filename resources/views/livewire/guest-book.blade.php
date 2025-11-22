<div x-data="{ open: false }">
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

                @auth
                    <a href="{{ route('my-bookings') }}" class="nav-item nav-link">My Bookings</a>
                    <a href="{{ route('lost-items') }}" class="nav-item nav-link">Lost and Found Items</a>

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
                    {{-- <div class="p-4 overflow-auto" style="max-height: calc(100vh - 65px);">
                        @forelse($notifications as $notification)
                            @php
                                $url = $notification->data['actions'][0]['url'] ?? '';

                                $parentUrl = dirname($url);
                                $bookingId = basename($parentUrl);
                            @endphp

                            <a href="{{ route('view-booking', $bookingId) }}"
                                class="text-decoration-none text-dark d-block">
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
                    </div> --}}
                    <div class="p-4 overflow-auto" style="max-height: calc(100vh - 65px);">
                        @forelse($notifications as $notification)
                            @php
                                $url = $notification->data['actions'][0]['url'] ?? '';

                                // $parentUrl = dirname($url);
                                // $bookingId = basename($parentUrl);

                            @endphp

                            <a class="text-decoration-none text-dark d-block" href="{{ $url }}">
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
            {{-- <a href="{{ route('my-bookings') }}" class="nav-item nav-link">My Bookings</a>
                <a href="{{ route('lost-items') }}" class="nav-item nav-link">Lost and Found Items</a>

                <a class="nav-item nav-link position-relative">
                    <i class="fa fa-bell fs-5"></i>
                    <span class="position-absolute translate-middle badge rounded-pill bg-danger">
                        3
                        <span class="visually-hidden">unread messages</span>
                    </span>
                </a>
            </div>
            <a href="" class="btn btn-primary rounded-pill py-2 px-4" wire:click.prevent="logout">Logout</a> --}}
        </nav>

        {{-- <div class="container-fluid bg-primary py-5 mb-5 hero-header">
            <div class="container py-5">
                <div class="row justify-content-center py-5">
                    <div class="col-lg-10 pt-lg-5 mt-lg-5 text-center">
                        <h1 class="display-3 text-white animated slideInDown">Book Now</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb justify-content-center">
                                <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                                <li class="breadcrumb-item text-white active" aria-current="page">Book Now</li>
                            </ol>
                        </nav>
                        <div class="position-relative w-75 mx-auto animated slideInDown mb-4">
                            <input class="form-control border-0 rounded-pill w-100 py-3 ps-4 pe-5" type="text"
                                placeholder="Search for Resort" wire:model.live="search">
                            <button type="button"
                                class="btn btn-primary rounded-pill py-2 px-4 position-absolute top-0 end-0 me-2"
                                style="margin-top: 7px;" wire:click.prevent="searchResorts">Search</button>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
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
                <h1 class="display-3 text-white animated slideInDown">Book Now</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                        <li class="breadcrumb-item text-white active" aria-current="page">Book Now</li>
                    </ol>
                </nav>
                <div class="position-relative w-75 mx-auto animated slideInDown mb-4">
                    <input class="form-control border-0 rounded-pill w-100 py-3 ps-4 pe-5" type="text"
                        placeholder="Search for Resort" wire:model.live="search">
                    <button type="button"
                        class="btn btn-primary rounded-pill py-2 px-4 position-absolute top-0 end-0 me-2"
                        style="margin-top: 7px;" wire:click.prevent="searchResorts">Search</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Navbar & Hero End -->


    <!-- Package Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h6 class="section-title bg-white text-center text-primary px-3">Resorts</h6>
                <h1 class="mb-5">Choose Resorts</h1>
            </div>
            <div class="row g-4 justify-content-center">
                @if ($resorts->isNotEmpty())
                    @foreach ($resorts as $resort)
                        <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                            <div class="package-item">
                                <div class="overflow-hidden">
                                    <img class="img-fluid"
                                        src="{{ $resort->image ? asset('resorts-photo/' . $resort->image) : asset('img/package-1.jpg') }}"
                                        alt="" style="width: 500px; height: 250px; object-fit: cover;">
                                </div>
                                <div class="d-flex border-bottom">
                                    <small class="flex-fill text-center border-end py-2"><i
                                            class="fa fa-map-marker-alt text-primary me-2"></i>{{ $resort->name }}</small>
                                </div>
                                <div class="text-center p-4">
                                    <div class="mb-3">
                                        <small class="fa fa-star text-primary"></small>
                                        <small class="fa fa-star text-primary"></small>
                                        <small class="fa fa-star text-primary"></small>
                                        <small class="fa fa-star text-primary"></small>
                                        <small class="fa fa-star text-primary"></small>
                                    </div>
                                    <div class="d-flex justify-content-center mb-2">
                                        {{-- <a href="#" class="btn btn-sm btn-primary px-3 border-end"
                                            style="border-radius: 30px 0 0 30px;">GPS</a> --}}
                                        <a href="#" class="btn btn-sm btn-primary px-3"
                                            style="border-radius: 30px 30px 30px 30px;"
                                            wire:click.prevent="bookResort({{ $resort->id }})">Visit Resort</a>
                                        {{-- <a href="#" class="btn btn-sm btn-primary px-3"
                                            style="border-radius: 30px 30px 30px 30px;"
                                            wire:click.prevent="bookResort({{ $resort->id }})">Visit Resort</a> --}}
                                    </div>
                                </div>
                                {{-- <div class="text-center p-4">
                                    <div class="d-flex justify-content-center mb-2">
                                        <a href="#" class="btn btn-sm btn-primary px-3"
                                            style="border-radius: 30px 30px 30px 30px;"
                                            wire:click.prevent="bookResort({{ $resort->id }})">Visit Resort</a>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                    @endforeach
                @else
                    <p>No resorts found.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Package End -->
    <!-- Footer Start -->
    <div class="container-fluid
                                            bg-dark text-light footer pt-5 mt-5 wow fadeIn"
        data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-3">Contact</h4>
                    <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>Anilao Port Anilao
                        Proper , Mabini ,
                        Davao De Oro , Philippines</p>
                    <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>0969 643
                        3420</p>
                    <p class="mb-2"><i class="fa fa-envelope me-3"></i>icmomabini@gmail.com</p>
                    <p class="mb-2"><i class="fab fa-facebook me-3"></i>Mabini ICM
                        Tourism Office</p>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="copyright">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        &copy; <a class="border-bottom" href="#">HANAPBOK</a>,
                        All Right Reserved.
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->
    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
</div>
