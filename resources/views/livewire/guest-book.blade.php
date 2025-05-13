<div>
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
                <a href="{{ route('my-bookings') }}" class="nav-item nav-link">My Bookings</a>
                <a href="{{ route('lost-items') }}" class="nav-item nav-link">Lost Items</a>
            </div>
            <a href="" class="btn btn-primary rounded-pill py-2 px-4" wire:click.prevent="logout">Logout</a>
        </nav>

        <div class="container-fluid bg-primary py-5 mb-5 hero-header">
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
                                    <div class="d-flex justify-content-center mb-2">
                                        <a href="#" class="btn btn-sm btn-primary px-3"
                                            style="border-radius: 30px 30px 30px 30px;"
                                            wire:click.prevent="bookResort({{ $resort->id }})">Visit Resort</a>
                                    </div>
                                </div>
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
    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
</div>
