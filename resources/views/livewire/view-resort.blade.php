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

                <a class="nav-item nav-link position-relative">
                    <i class="fa fa-bell fs-5"></i>
                    <span class="position-absolute translate-middle badge rounded-pill bg-danger">
                        3
                        <span class="visually-hidden">unread messages</span>
                    </span>
                </a>
            </div>
            <a href="" class="btn btn-primary rounded-pill py-2 px-4" wire:click.prevent="logout">Logout</a>
        </nav>

        {{-- <div class="container-fluid bg-primary py-5 mb-5 hero-header">
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
    <!-- Navbar & Hero End -->


    @if ($this->activePage == 'view')
        <!-- About Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <div class="row g-5">
                    <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s" style="min-height: 400px;">
                        <div class="position-relative h-100">
                            <img class="img-fluid position-absolute w-100 h-100"
                                src="{{ $record->image ? asset('resorts-photo/' . $record->image) : asset('img/package-1.jpg') }}"
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
    @endif

    @if ($this->activePage == 'booking')
        <!-- Booking Start -->
        <div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
            <div class="container">
                <div class="booking p-5">
                    <div class="row g-5 align-items-center">
                        <div class="col-md-6 text-white">
                            {{-- <h6 class="text-white text-uppercase">Booking</h6> --}}
                            <h1 class="text-white mb-4">Online Booking</h1>
                            <p class="mb-4">{{ $record->description }}</p>
                            <a class="btn btn-outline-light py-3 px-5 mt-2" href=""
                                wire:click.prevent="viewInformation">View
                                Information</a>
                        </div>
                        <div class="col-md-6">
                            <h1 class="text-white mb-4">List of Person</h1>
                            <div>
                                {{-- <form wire:submit.prevent="submitEntranceFees"> --}}
                                @foreach ($items as $index => $item)
                                    <div class="row g-3 mb-3" wire:key="item-{{ $index }}">
                                        <div class="{{ count($items) > 1 ? 'col-md-5' : 'col-md-6' }}">
                                            <div class="form-floating">
                                                <select class="form-select" id="select-{{ $index }}"
                                                    wire:model="items.{{ $index }}.entrance_fee_id">
                                                    <option value="">Select Type</option>
                                                    @foreach ($entranceFees as $entranceFee)
                                                        @if (
                                                            !collect($items)->where('entrance_fee_id', $entranceFee->id)->where(function ($i, $key) use ($index) {
                                                                    // Check if the current item in the $items collection is NOT the current row
                                                                    return $key !== $index;
                                                                })->isNotEmpty())
                                                            <option value="{{ $entranceFee->id }}">
                                                                {{ $entranceFee->name }} -
                                                                {{ $entranceFee->type }} -
                                                                ₱{{ $entranceFee->price }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                <label for="select-{{ $index }}">Type</label>
                                            </div>
                                            @error('items.' . $index . '.entrance_fee_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="{{ count($items) > 1 ? 'col-md-5' : 'col-md-6' }}">
                                            <div class="form-floating">
                                                <input type="number" class="form-control"
                                                    id="quantity-{{ $index }}" placeholder="Quantity"
                                                    wire:model="items.{{ $index }}.quantity" min="1">
                                                <label for="quantity-{{ $index }}">Quantity</label>
                                            </div>
                                            @error('items.' . $index . '.quantity')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        @if (count($items) > 1)
                                            <div class="col-md-2 d-flex align-items-end">
                                                <button type="button" class="btn btn-danger"
                                                    wire:click="removeItem({{ $index }})">
                                                    X
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                                <div class="d-flex justify-content-end align-items-center">
                                    <button type="button" class="btn btn-primary rounded-pill py-1 px-3 mt-2"
                                        wire:click="addItem">
                                        Add Item
                                    </button>
                                </div>
                                {{-- </form> --}}
                            </div>

                            <h1 class="text-white mb-4">Accomodation</h1>
                            <div>
                                {{-- <form wire:submit.prevent="submitEntranceFees"> --}}
                                @foreach ($cottageRooms as $index => $item)
                                    <div class="row g-3 mb-3" wire:key="item-{{ $index }}">
                                        <div class="{{ count($cottageRooms) > 1 ? 'col-md-5' : 'col-md-6' }}">
                                            <div class="form-floating">
                                                <select class="form-select" id="select-{{ $index }}"
                                                    wire:model="cottageRooms.{{ $index }}.cottage_id">
                                                    <option value="">Select Cottage</option>
                                                    @foreach ($resort as $cottage)
                                                        @if (
                                                            !collect($cottageRooms)->where('cottage_id', $cottage->id)->where(function ($i, $key) use ($index) {
                                                                    // Check if the current item in the $cottageRooms collection is NOT the current row
                                                                    return $key !== $index;
                                                                })->isNotEmpty())
                                                            <option value="{{ $cottage->id }}">
                                                                {{ $cottage->name }} -
                                                                {{ $cottage->type }} -
                                                                ₱{{ $cottage->price }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                <label for="select-{{ $index }}">Cottage</label>
                                            </div>
                                            @error('cottageRooms.' . $index . '.cottage_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="{{ count($cottageRooms) > 1 ? 'col-md-5' : 'col-md-6' }}">
                                            <div class="form-floating">
                                                <input type="number" class="form-control"
                                                    id="quantity-{{ $index }}" placeholder="Quantity"
                                                    wire:model="cottageRooms.{{ $index }}.quantity"
                                                    min="1">
                                                <label for="quantity-{{ $index }}">Quantity</label>
                                            </div>
                                            @error('cottageRooms.' . $index . '.quantity')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        @if (count($cottageRooms) > 1)
                                            <div class="col-md-2 d-flex align-items-end">
                                                <button type="button" class="btn btn-danger"
                                                    wire:click="removeResortItem({{ $index }})">
                                                    X
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                                <div class="d-flex justify-content-end align-items-center">
                                    <button type="button" class="btn btn-primary rounded-pill py-1 px-3 mt-2"
                                        wire:click="addResortItem">
                                        Add Item
                                    </button>
                                </div>
                            </div>

                            <div class="mt-4">
                                <div>
                                    <div class="row g-3 mb-3" wire:key="item-{{ $index }}">
                                        <div class='col-md-6'>
                                            <div class="form-floating">
                                                <input type="date" class="form-control" wire:model="date" required
                                                    min="{{ \Carbon\Carbon::today()->toDateString() }}">
                                                <label>Date From</label>
                                            </div>
                                            @error('date')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class='col-md-6'>
                                            <div class="form-floating">
                                                <input type="date" class="form-control" wire:model="date_to"
                                                    required min="{{ \Carbon\Carbon::today()->toDateString() }}">
                                                <label>Date To</label>
                                            </div>
                                            @error('date_to')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <div>
                                        <div class="row g-3 mb-3" wire:key="item-{{ $index }}">
                                            <div class='col-md-12'>
                                                <div class="form-floating">
                                                    <select class="form-select" wire:model="payment_type">
                                                        <option value="">Select Payment Type</option>
                                                        <option value="gcash">GCash</option>
                                                        <option value="walk_in">Walk In</option>
                                                    </select>
                                                    <label>Payment Type</label>
                                                </div>
                                                @error('payment_type')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                        </div>
                                    </div>

                                    <div class="col-12 mt-4">
                                        {{-- <button class="btn btn-outline-light w-100 py-3"
                                            wire:click.prevent='submit'>Book
                                            Now</button> --}}
                                        <!-- Button trigger modal -->
                                        <button type="button" class="btn btn-outline-light w-100 py-3"
                                            data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                            Book Now
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static"
                                            data-bs-keyboard="false" tabindex="-1"
                                            aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="staticBackdropLabel"></h1>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure you would like to do this?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="button" wire:click.prevent='submit'
                                                            class="btn btn-primary">Confirm</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Booking Start -->
    @endif

    <!-- Package Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h6 class="section-title bg-white text-center text-primary px-3">ACCOMODATIONS</h6>
                <h1 class="mb-5">Rooms & Cottages</h1>
            </div>
            <div class="row g-4 justify-content-center">
                @foreach ($record->items ?? [] as $item)
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                        <div class="package-item">
                            <div class="text-center p-4">
                                <h3 class="mb-0">₱ {{ $item['price'] }}</h3>
                                <p>{{ $item['name'] }}</p>
                                <p style="font-size: 12px;">
                                    {{ isset($item['type']) ? ($item['type'] == 'day_tour' ? 'Day Tour' : 'Night Tour') : '---' }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Package End -->
    <div class="container-fluid bg-dark text-light footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-white mb-3">Contact</h4>
                    <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>Anilao Port Anilao Proper , Mabini ,
                        Davao De Oro , Philippines</p>
                    <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>0969 643 3420</p>
                    <p class="mb-2"><i class="fa fa-envelope me-3"></i>icmomabini@gmail.com</p>
                    <p class="mb-2"><i class="fab fa-facebook me-3"></i>Mabini ICM Tourism Office</p>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="copyright">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        &copy; <a class="border-bottom" href="#">HANAPBOK</a>, All Right Reserved.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
</div>
<script>
    window.addEventListener('close-modal', () => {
        const modalEl = document.getElementById('staticBackdrop');
        const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
        modal.hide();
    });
</script>
