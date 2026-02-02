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
                <a href="{{ route('guest-booking') }}" class="nav-item nav-link">Book Now</a>
                <a href="{{ route('my-bookings') }}" class="nav-item nav-link">My Bookings</a>
                <a href="{{ route('lost-items') }}" class="nav-item nav-link active">Lost and Found Items</a>

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
            </div>
            <a href="" class="btn btn-primary py-2 px-4" wire:click.prevent="logout">Logout</a>
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
                <h1 class="display-3 text-white animated slideInDown">Lost and Found Items</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                        <li class="breadcrumb-item text-white active" aria-current="page">Lost and Found Items</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>


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
                <button wire:click="markAllAsRead" class="btn btn-sm btn-outline-success" title="Mark all as read">
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

                <a href="{{ route('view-booking', $bookingId) }}" class="text-decoration-none text-dark d-block">
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
                            {{-- <div class="mt-4">
                                <div>
                                    <div class="form-floating">
                                        <input type="datetime-local" class="form-control" wire:model="date" required
                                            max="{{ now()->format('Y-m-d\TH:i') }}">
                                        <label>Date</label>
                                    </div>
                                    @error('date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div> --}}
                            <div class="mt-4">
                                <div class="form-floating">
                                    <input type="datetime-local" class="form-control" id="date"
                                        wire:model="date" required max="{{ now()->format('Y-m-d\TH:i') }}"
                                        onchange="confirmDate(event)">
                                    <label>Date</label>
                                </div>

                                @error('date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
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
                            {{-- <div class="col-12 mt-4">
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
                                                <button type="button" wire:click.prevent="report"
                                                    wire:loading.attr="disabled"
                                                    class="btn btn-outline-light w-100 py-3">
                                                    <span wire:loading.remove>Confirm</span>
                                                    <span wire:loading>Processing...</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="col-12 mt-4">
                                <button type="button" class="btn btn-outline-light w-100 py-3"
                                    data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                    Submit
                                </button>

                                <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static"
                                    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="staticBackdropLabel">
                                                </h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Would you like to proceed?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="button" wire:click.prevent="report"
                                                    wire:loading.attr="disabled" class="btn btn-primary">
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
                            <th>Action</th>
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
                                <td>
                                    <a href="{{ route('view-reports', $item->id) }}"
                                        class="btn btn-sm btn-primary me-2">View</a>
                                </td>
                            </tr>
                        @endforeach
                </table>
            </div>
        </div>
    @endif
    <!-- Package Start -->

    <!-- Package End -->
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
    <script>
        function confirmDate(event) {
            const selected = event.target.value;

            if (!confirm("Use this date?\n\n" + selected)) {
                event.target.value = "";
                event.target.dispatchEvent(new Event('input'));
            }
        }
    </script>
</div>
