<div>
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
                <a href="{{ route('lost-items') }}" class="nav-item nav-link">Lost and Found Items</a>
            </div>
            <a href="" class="btn btn-primary py-2 px-4" wire:click.prevent="logout">Logout</a>
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
            <div class="hero-overlay"></div>
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
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">
                                    <i class="fa fa-info-circle me-2"></i> Information
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row p-1">
                                    {{ $record->description }}
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">
                                    <i class="fa fa-info-circle me-2"></i> Contact Details
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @forelse ($record?->others ?? [] as $other)
                                        <div class="col-md-6 col-lg-4 mb-2">
                                            <p class="mb-0 d-flex align-items-center">
                                                <i class="fa fa-check-circle text-success me-2"></i>
                                                <span class="text-dark">{{ $other['name'] }}</span>
                                            </p>
                                        </div>
                                    @empty
                                        <div class="col-12">
                                            <p class="text-muted fst-italic mb-0">No other details available.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-sm mb-2">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">
                                    <i class="fa fa-ticket-alt me-2"></i> Entrance Fees
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="list-group list-group-flush">
                                    @forelse ($record?->entranceFees ?? [] as $entranceFee)
                                        @php
                                            $tourType = 'Free';
                                            $badgeClass = 'bg-secondary';

                                            if (($entranceFee['type'] ?? '') === 'night_tour') {
                                                $tourType = 'Night Tour';
                                                $badgeClass = 'bg-dark';
                                            } elseif (($entranceFee['type'] ?? '') === 'day_tour') {
                                                $tourType = 'Day Tour';
                                                $badgeClass = 'bg-warning text-dark';
                                            }
                                        @endphp
                                        <div
                                            class="list-group-item d-flex justify-content-between align-items-center px-0">
                                            <div>
                                                <span
                                                    class="badge {{ $badgeClass }} me-2">{{ $tourType }}</span>
                                                <span class="fw-bold text-wrap">{{ $entranceFee['name'] }}</span>
                                            </div>
                                            <span class="h6 mb-0 text-success fw-bolder">
                                                ₱{{ number_format($entranceFee['price'] ?? 0, 2) }}
                                            </span>
                                        </div>
                                    @empty
                                        <p class="text-muted fst-italic mb-0">No entrance fees listed.</p>
                                    @endforelse
                                </div>
                            </div>
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
                            <h1 class="text-white mb-4">Online Booking</h1>
                            <p class="mb-4">{{ $record->description }}</p>
                            <a class="btn btn-outline-light py-3 px-5 mt-2" href=""
                                wire:click.prevent="viewInformation">View
                                Information</a>
                        </div>
                        <div class="col-md-6">
                            <h1 class="text-white mb-4">Accomodation</h1>
                            <div>
                                @foreach ($cottageRooms as $cottageIndex => $item)
                                    <div class="row g-3 mb-3" wire:key="cottage-item-{{ $cottageIndex }}">

                                        <div class="{{ count($cottageRooms) > 1 ? 'col-md-10' : 'col-md-12' }}">
                                            <div class="form-floating">
                                                <select class="form-select" id="select-cottage-{{ $cottageIndex }}"
                                                    wire:model.live="cottageRooms.{{ $cottageIndex }}.cottage_id">
                                                    <option value="">Select Cottage</option>
                                                    {{-- @foreach ($resort as $cottage)
                                                        @if (!collect($cottageRooms)->where('cottage_id', $cottage->id)->where(function ($i, $key) use ($cottageIndex) {
            return $key !== $cottageIndex;
        })->isNotEmpty())
                                                            <option value="{{ $cottage->id }}">
                                                                {{ $cottage->name }} -
                                                                {{ $cottage->type }} -
                                                                ₱{{ number_format($cottage->price ?? 0, 2) }}
                                                            </option>
                                                        @endif
                                                    @endforeach --}}
                                                    @foreach ($resort as $cottage)
                                                        <option value="{{ $cottage->id }}">
                                                            {{ $cottage->name }} -
                                                            {{ $cottage->type }} -
                                                            ₱{{ number_format($cottage->price ?? 0, 2) }}
                                                        </option>
                                                    @endforeach

                                                </select>
                                                <label for="select-cottage-{{ $cottageIndex }}">Cottage</label>
                                            </div>
                                            @error('cottageRooms.' . $cottageIndex . '.cottage_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        @if (count($cottageRooms) > 1)
                                            <div class="col-md-2 d-flex align-items-end">
                                                <button type="button" class="btn btn-danger w-100"
                                                    wire:click="removeResortItem({{ $cottageIndex }})">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <h1 class="text-white mb-4">List of Guest</h1>

                            <div>
                                @php
                                    $firstCottageId = $cottageRooms[0]['cottage_id'] ?? null;
                                    $mainBookingType = '';

                                    if ($firstCottageId) {
                                        $selectedCottage = collect($resort)->firstWhere('id', $firstCottageId);
                                        $mainBookingType = $selectedCottage['type'] ?? '';
                                    }

                                    $showEntranceFees = in_array($mainBookingType, ['day_tour', 'night_tour']);
                                @endphp

                                @foreach ($items as $index => $item)
                                    <div class="row g-3 mb-3" wire:key="guest-item-{{ $index }}">

                                        <div class="{{ count($items) > 1 ? 'col-md-5' : 'col-md-6' }}">
                                            <div class="form-floating">
                                                <select class="form-select" id="select-guest-{{ $index }}"
                                                    wire:model="items.{{ $index }}.entrance_fee_id"
                                                    {{ $showEntranceFees ? '' : 'disabled' }}>
                                                    <option value="">Select Type</option>

                                                    @if ($showEntranceFees)
                                                        @foreach ($entranceFees as $entranceFee)
                                                            @if ($entranceFee->type === $mainBookingType)
                                                                @if (
                                                                    !collect($items)->where('entrance_fee_id', $entranceFee->id)->where(function ($i, $key) use ($index) {
                                                                            return $key !== $index;
                                                                        })->isNotEmpty())
                                                                    <option value="{{ $entranceFee->id }}">
                                                                        {{ $entranceFee->name }} -
                                                                        {{ ucfirst(str_replace('_', ' ', $entranceFee->type)) }}
                                                                        -
                                                                        ₱{{ number_format($entranceFee->price ?? 0, 2) }}
                                                                    </option>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <option value="" disabled>Select Cottage First</option>
                                                    @endif

                                                </select>
                                                <label for="select-guest-{{ $index }}">Type</label>
                                            </div>
                                            @error('items.' . $index . '.entrance_fee_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="{{ count($items) > 1 ? 'col-md-5' : 'col-md-6' }}">
                                            <div class="form-floating">
                                                <input type="number" class="form-control"
                                                    id="quantity-guest-{{ $index }}" placeholder="Quantity"
                                                    wire:model="items.{{ $index }}.quantity" min="1"
                                                    {{ $showEntranceFees ? '' : 'disabled' }}>
                                                <label for="quantity-guest-{{ $index }}">Quantity</label>
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

                                @error('overQuantity')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror

                                <div class="d-flex justify-content-end align-items-center">
                                    <button type="button" class="btn btn-primary py-1 px-3 mt-2"
                                        wire:click="addItem" {{ $showEntranceFees ? '' : 'disabled' }}>
                                        Add Item
                                    </button>
                                </div>
                            </div>

                            <div class="mt-4">
                                <div>
                                    <div class="row g-3 mb-3">
                                        <div class='col-md-6'>
                                            <div class="form-floating">
                                                <input type="date" class="form-control" wire:model.live="date"
                                                    required min="{{ now()->format('Y-m-d') }}">
                                                <label>Date From</label>
                                            </div>
                                            @error('date')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class='col-md-6'>
                                            <div class="form-floating">
                                                <input type="date" class="form-control" wire:model.live="date_to"
                                                    required @if (!$date) disabled @endif
                                                    min="{{ $date ? \Carbon\Carbon::parse($date)->format('Y-m-d') : now()->format('Y-m-d') }}">

                                                <label>Date To</label>
                                            </div>
                                            @error('date_to')
                                                <span class="text-danger small">{{ $message }}</span>
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
                                                        {{-- <option value="walk_in">Walk In</option> --}}
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
                                        <div class="col-12 mt-4">

                                            {{-- 1. BOOK NOW BUTTON: Opens the modal for confirmation --}}
                                            <button type="button" class="btn btn-outline-light w-100 py-3"
                                                data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                                Book Now
                                            </button>

                                            {{-- 2. MODAL STRUCTURE (Uncommented and Cleaned Up) --}}
                                            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static"
                                                data-bs-keyboard="false" tabindex="-1"
                                                aria-labelledby="staticBackdropLabel" aria-hidden="true"
                                                wire:ignore.self> {{-- Added wire:ignore.self for stability --}}

                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="staticBackdropLabel">
                                                                Confirm Booking
                                                            </h1>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Are you sure you would like to do this?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-danger"
                                                                data-bs-dismiss="modal">Close</button>

                                                            {{-- 3. CONFIRM BUTTON: Triggers Livewire submit and closes modal --}}
                                                            <button type="button" wire:click.prevent="submit"
                                                                wire:loading.attr="disabled" class="btn btn-primary"
                                                                data-bs-dismiss="modal"> {{-- CRITICAL FIX: Closes modal after click --}}
                                                                <span wire:loading.remove>Confirm</span>
                                                                <span wire:loading>Processing...</span>
                                                            </button>
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
                    {{-- @if ($item['is_occupied'] == true) --}}
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                        <div class="package-item">
                            <div class="text-center p-4">

                                <h3 class="mb-0">₱ {{ number_format($item['price'] ?? 0, 2) }}</h3>
                                <p>{{ ucfirst($item['name']) }} </p>
                                <span
                                    class="{{ $item['is_occupied'] ? 'text-danger' : 'text-success' }} fw-bold">{{ $item['is_occupied'] ? 'Occupied' : '' }}</span>
                                <p style="font-size: 12px;">
                                    {{ isset($item['type']) ? ($item['type'] == 'day_tour' ? 'Day Tour' : 'Night Tour') : '---' }}
                                </p>

                                <button type="button" class="btn btn-sm btn-primary mt-2" data-bs-toggle="modal"
                                    data-bs-target="#imageModal-{{ $loop->index }}">
                                    View
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="imageModal-{{ $loop->index }}" tabindex="-1"
                        aria-labelledby="imageModalLabel-{{ $loop->index }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="imageModalLabel-{{ $loop->index }}">
                                        {{ ucfirst($item['name']) }} Image</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="p-2 mb-2">
                                    <h1>{{ $item['description'] }}</h1>
                                    <p>
                                        Number of allowed guests:
                                        <span class="fw-bold">
                                            {{ $item['number_person'] }}
                                        </span>
                                    </p>
                                    <p>
                                        Check-in:
                                        <span class="fw-bold">
                                            {{ date('g:i A', strtotime($item['check_in'])) }}
                                        </span>
                                    </p>
                                    <p>
                                        Check-out:
                                        <span class="fw-bold">
                                            {{ date('g:i A', strtotime($item['check_out'])) }}
                                        </span>
                                    </p>
                                </div>
                                <div class="modal-body text-center">
                                    <img src="{{ asset('accommodations-photo/' . $item->image) }}" class="img-fluid"
                                        alt="{{ ucfirst($item['name']) }}">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- @endif --}}
                @endforeach
            </div>
        </div>
    </div>
    <!-- Package End -->

    <!-- Testimonial Start -->
    <div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container">
            <div class="text-center">
                <h6 class="section-title bg-white text-center text-primary px-3">Reviews</h6>
                <h1 class="mb-5">Clients Feedback</h1>
            </div>
            <div class="owl-carousel testimonial-carousel position-relative">

                @foreach ($reviews as $review)
                    <div class="testimonial-item bg-white text-center border p-4">
                        {{-- <img class="bg-white rounded-circle shadow p-1 mx-auto mb-3"
                        src="{{ asset('carousel/carousel1.jpg') }}" style="width: 80px; height: 80px;"> --}}
                        <h5 class="mb-0">{{ $review->user->name }}</h5>
                        {{-- <p>New York, USA</p> --}}
                        <p class="mb-0">{{ $review->review }}</p>

                        <div class="d-flex justify-content-center mt-3">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $review->rating)
                                    <i class="fa fa-star text-secondary"></i>
                                @else
                                    <i class="fa fa-star"></i>
                                @endif
                            @endfor
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Testimonial End -->


    <div class="container-fluid bg-dark text-light text-center py-5 mt-5">
        <h3 class="text-white mb-4">Contact Us</h3>

        <div class="d-flex justify-content-center gap-4 mb-3">
            <div>
                <i class="fa fa-phone fa-2x text-primary mb-2"></i>
                <p>0969 643 3420</p>
            </div>
            <div>
                <i class="fa fa-envelope fa-2x text-primary mb-2"></i>
                <p>icmomabini@gmail.com</p>
            </div>
            <div>
                <i class="fa fa-map-marker-alt fa-2x text-primary mb-2"></i>
                <p>Anilao Port, Mabini</p>
            </div>
        </div>

        <div class="d-flex justify-content-center gap-3 mt-3">
            <a class="btn btn-light rounded-circle" href="https://www.facebook.com/ICMOmabini2024"><i
                    class="fab fa-facebook-f"></i></a>
            <a class="btn btn-light rounded-circle"
                href="https://www.pangasinan.gov.ph/city-municipalities/mabini/#:~:text=Tourism%2Dwise%2C%20the%20town%20of,formations%20of%20stalagmites%20and%20stalactites"><i
                    class="fab fa-chrome"></i></a>
        </div>

        <hr class="border-secondary w-50 mx-auto my-4">

        <p class="mb-0">&copy; <strong>HANAPBOK</strong> — All Rights Reserved.</p>
    </div>

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('lib/wow/wow.min.js') }}"></script>
    <script src="{{ asset('lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('lib/tempusdominus/js/moment.min.js') }}"></script>
    <script src="{{ asset('lib/tempusdominus/js/moment-timezone.min.js') }}"></script>
    <script src="{{ asset('lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <script src="{{ asset('js/guest/js/main.js') }}"></script>

</div>
<script>
    window.addEventListener('close-modal', () => {
        const modalEl = document.getElementById('staticBackdrop');
        const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
        modal.hide();
    });
</script>
