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
                <a href="{{ route('guest-booking') }}" class="nav-item nav-link">Book Now</a>
                <a href="{{ route('my-bookings') }}" class="nav-item nav-link active">My Bookings</a>
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
                        <h1 class="display-3 text-white animated slideInDown">Hello {{ auth()->user()->name }}!
                        </h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb justify-content-center">
                                <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                                <li class="breadcrumb-item text-white active" aria-current="page">
                                    My Bookings</li>
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
                <h1 class="display-3 text-white animated slideInDown">Hello {{ auth()->user()->name }}!
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                        <li class="breadcrumb-item text-white active" aria-current="page">
                            My Bookings</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- Navbar & Hero End -->

    <div class="container-xxl py-5">
        <div class="container">
            <table id="dataTable" class="table nowrap">
                <thead>
                    <tr>
                        <th>Resort</th>
                        <th>Booking Date</th>
                        <th>Payment Type</th>
                        <th>Status</th>
                        <th>Amount to Pay</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($record as $item)
                        <tr>
                            <td>{{ $item->resort->name }}</td>
                            <td>{{ $item->created_at->format('F j, Y') }}</td>
                            <td>{{ $item->is_partial ? 'Partial Payment' : 'Full Payment' }} -
                                {{ $item->payment_type == 'gcash' ? 'GCash' : 'Walk In' }}
                            </td>
                            <td>{{ ucfirst($item->status) }}</td>
                            <td>{{ $item->amount_to_pay }}</td>
                            <td class="text-end">
                                <a href="{{ route('view-booking', $item->id) }}"
                                    class="btn btn-sm btn-primary me-2">View</a>

                                @if ($item->status === 'confirmed')
                                    <a class="btn btn-sm btn-danger me-2"
                                        wire:click.prevent="cancelBooking({{ $item->id }})">Cancel</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
            </table>
        </div>
    </div>

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
    $('#dataTable').DataTable({
        layout: {
            topStart: {}
        }
    });
</script>
