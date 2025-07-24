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
                <a href="{{ route('my-bookings') }}" class="nav-item nav-link">My Bookings</a>
                <a href="{{ route('lost-items') }}" class="nav-item nav-link active">Lost and Found Items</a>

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
                        <h1 class="display-3 text-white animated slideInDown">Lost Items</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb justify-content-center">
                                <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                                <li class="breadcrumb-item text-white active" aria-current="page">Lost Items</li>
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
                <h1 class="display-3 text-white animated slideInDown">Lost Items</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                        <li class="breadcrumb-item text-white active" aria-current="page">Lost Items</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- Navbar & Hero End -->

    @if ($this->activePage == 'create')
        <div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
            <div class="container">
                <div class="booking p-5">
                    <div class="row g-5 align-items-center">
                        <div class="col-md-6 text-white">
                            <h1 class="text-white mb-4">Report Items </h1>
                        </div>
                        <div class="col-md-6">
                            <div class="mt-4">
                                <div>
                                    <div class="form-floating">
                                        <select class="form-select" wire:model="selectResort" required>
                                            <option value="">Select Resort</option>
                                            @foreach ($resorts as $resort)
                                                <option value="{{ $resort->id }}">{{ $resort->name }}</option>
                                            @endforeach
                                        </select>
                                        <label>Resort</label>
                                    </div>
                                    @error('resort')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="mt-4">
                                <div>
                                    <div class="form-floating">
                                        <select class="form-select" wire:model="type" required>
                                            <option value="">Select Type</option>
                                            <option value="lost_item">Lost Item</option>
                                            <option value="found_item">Found Item</option>
                                        </select>
                                        <label>Type</label>
                                    </div>
                                    @error('type')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="mt-4">
                                <div>
                                    <div class="form-floating">
                                        <input type="text" class="form-control" wire:model="description" required>
                                        <label>Description</label>
                                    </div>
                                    @error('description')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="mt-4">
                                <div>
                                    <div class="form-floating">
                                        <input type="datetime-local" class="form-control" wire:model="date" required>
                                        <label>Date</label>
                                    </div>
                                    @error('date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="mt-4">
                                <div>
                                    <div class="form-floating">
                                        <input type="text" class="form-control" wire:model="location" required>
                                        <label>Location</label>
                                    </div>
                                    @error('location')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="mt-4">
                                <div>

                                    <label for="formFile" class="form-label">Upload Image</label>
                                    <input class="form-control @error('uploadPhoto') is-invalid @enderror"
                                        type="file" id="formFile" accept="image/*" wire:model="uploadPhoto">

                                    @error('uploadPhoto')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div id="uploading-indicator" class="mt-2 text-info" style="display: none;">
                                        <span class="spinner-border spinner-border-sm me-2" role="status"
                                            aria-hidden="true"></span>
                                        Uploading image...
                                    </div>
                                    <div id="upload-error" class="mt-2 text-danger" style="display: none;"></div>
                                    <div id="upload-success" class="mt-2 text-success" style="display: none;"></div>
                                    <div id="image-preview" class="mt-3">
                                        @if ($uploadPhoto)
                                            <img src="{{ $uploadPhoto->temporaryUrl() }}" alt="Payment Preview"
                                                style="max-width: 100%; height: auto;">
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-4">
                                {{-- <button class="btn btn-outline-light w-100 py-3"
                                    wire:click.prevent='report'>Submit</button> --}}

                                <button type="button" class="btn btn-dark w-100 py-3" data-bs-toggle="modal"
                                    data-bs-target="#staticBackdrop">
                                    Submit
                                </button>

                                <!-- Modal -->
                                <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static"
                                    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="staticBackdropLabel"></h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you would like to do this?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="button" wire:click.prevent='report'
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
    @endif

    @if ($this->activePage == 'list')
        <div class="container-xxl py-5">
            <div class="text-end">
                <a class="btn btn-sm btn-primary me-2" wire:click="createReport">Report</a>
            </div>
            <div class="container">
                <table id="dataTable" class="table nowrap">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Resort</th>
                            <th>Location</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($record as $item)
                            <tr>
                                <td>{{ $item->type == 'lost_item' ? 'Lost Item' : 'Found Item' }}</td>
                                <td>{{ $item->resort->name }}</td>
                                <td>{{ $item->location }}</td>
                                <td>
                                    @switch($item->status)
                                        @case('found')
                                            Found
                                        @break

                                        @case('not_found')
                                            Not Found
                                        @break

                                        @case('claimed')
                                            Claimed
                                        @break

                                        @case('not_claimed')
                                            Not Claimed
                                        @break

                                        @default
                                            Unknown
                                    @endswitch
                                </td>
                            </tr>
                        @endforeach
                </table>
            </div>
        </div>
    @endif
    <!-- Package Start -->

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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @script
        <script>
            Livewire.on('swal:modal', event => {
                Swal.fire({
                    title: 'Success',
                    text: 'Report Submitted!',
                    icon: "success",
                });
            });
        </script>
    @endscript

    <script>
        $('#dataTable').DataTable({
            layout: {
                topStart: {}
            }
        });
    </script>

    <script>
        window.addEventListener('close-modal', () => {
            const modalEl = document.getElementById('staticBackdrop');
            const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            modal.hide();
        });
    </script>
</div>
