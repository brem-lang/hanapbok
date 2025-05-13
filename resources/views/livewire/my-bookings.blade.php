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
            </div>
            <a href="" class="btn btn-primary rounded-pill py-2 px-4" wire:click.prevent="logout">Logout</a>
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
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
</div>

<script>
    $('#dataTable').DataTable({
        layout: {
            topStart: {}
        }
    });
</script>
