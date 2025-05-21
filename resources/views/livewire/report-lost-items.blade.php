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
                <a href="{{ route('lost-items') }}" class="nav-item nav-link active">Lost Items</a>
            </div>
            <a href="" class="btn btn-primary rounded-pill py-2 px-4" wire:click.prevent="logout">Logout</a>
        </nav>

        <div class="container-fluid bg-primary py-5 mb-5 hero-header">
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
        </div>
    </div>
    <!-- Navbar & Hero End -->

    <div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container">
            <div class="booking p-5">
                <div class="row g-5 align-items-center">
                    <div class="col-md-6 text-white">
                        <h1 class="text-white mb-4">Report Lost Items </h1>
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
                                    <input type="date" class="form-control" wire:model="date" required>
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

                                <label for="formFile" class="form-label">Upload Image (Optional)</label>
                                <input class="form-control @error('uploadPhoto') is-invalid @enderror" type="file"
                                    id="formFile" accept="image/*" wire:model="uploadPhoto">

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
                            <button class="btn btn-outline-light w-100 py-3" wire:click.prevent='report'>Report</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Package Start -->

    <!-- Package End -->
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
</div>
