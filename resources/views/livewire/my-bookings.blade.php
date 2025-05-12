<div>
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
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarCollapse">
                        <span class="fa fa-bars"></span>
                    </button>
                    <div class="navbar-nav ms-auto py-0">
                        <a href="{{ route('index') }}" class="nav-item nav-link">Home</a>
                        <a href="{{ route('guest-booking') }}" class="nav-item nav-link">Book Now</a>
                        <a href="{{ route('my-bookings') }}" class="nav-item nav-link active">My Bookings</a>
                        <a href="{{ route('lost-items') }}" class="nav-item nav-link">Lost Items</a>
                    </div>
                    <a href="" class="btn btn-primary rounded-pill py-2 px-4"
                        wire:click.prevent="logout">Logout</a>
                </nav>

                <div class="container-fluid bg-primary py-5 mb-5 hero-header">
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
                                    <td>{{ ucfirst($item->status) }}</td>
                                    <td>{{ $item->amount_to_pay }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('view-booking', $item->id) }}"
                                            class="btn btn-sm btn-primary me-2">View</a>
                                    </td>
                                </tr>
                            @endforeach
                    </table>
                </div>
            </div>



            <!-- Back to Top -->
            <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i
                    class="bi bi-arrow-up"></i></a>


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

            <link href="https://cdn.datatables.net/2.3.0/css/dataTables.bootstrap5.css" rel="stylesheet">
            <link href="https://cdn.datatables.net/buttons/3.2.3/css/buttons.bootstrap5.css" rel="stylesheet">
            <link href="https://cdn.datatables.net/staterestore/1.4.1/css/stateRestore.bootstrap5.css" rel="stylesheet">

            <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
            <script src="https://cdn.datatables.net/2.3.0/js/dataTables.js"></script>
            <script src="https://cdn.datatables.net/2.3.0/js/dataTables.bootstrap5.js"></script>
            <script src="https://cdn.datatables.net/staterestore/1.4.1/js/dataTables.stateRestore.js"></script>
            <script src="https://cdn.datatables.net/staterestore/1.4.1/js/stateRestore.bootstrap5.js"></script>
            <script src="https://cdn.datatables.net/buttons/3.2.3/js/dataTables.buttons.js"></script>
            <script src="https://cdn.datatables.net/buttons/3.2.3/js/buttons.bootstrap5.js"></script>
        </body>
    </div>

    <script>
        $('#dataTable').DataTable({
            layout: {
                topStart: {}
            }
        });
    </script>
</div>
