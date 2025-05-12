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
                                <h1 class="display-3 text-white animated slideInDown">Booking Details</h1>
                                </h1>
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb justify-content-center">
                                        <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                                        <li class="breadcrumb-item"><a href="{{ route('my-bookings') }}">My Bookings</a>
                                        </li>
                                        <li class="breadcrumb-item text-white active" aria-current="page">
                                            Booking Details</li>
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
                    <div class="row g-5">
                        <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s" style="min-height: 400px;">
                            <div class="position-relative h-100">
                                <img class="img-fluid position-absolute w-100 h-100"
                                    src="{{ $record->resort->image ? asset('resorts-photo/' . $record->resort->image) : asset('img/about.jpg') }}"
                                    alt="" style="object-fit: cover;">
                            </div>
                            <div class="position-relative h-100 mt-3">
                                <h6 class=" bg-white text-start text-primary pe-3">Scan QR Code</h6>
                                <img class="img-fluid position-absolute" src="{{ asset('img/qrcode.svg') }}"
                                    alt="" style="object-fit: cover;">
                            </div>
                        </div>
                        <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.3s">
                            {{-- <h6 class="section-title bg-white text-start text-primary pe-3">INFORMATION</h6>
                            <p class="mb-2" style="font-size: 16px;">{{ $record->description }}</p> --}}
                            <h6 class="section-title bg-white text-start text-primary pe-3">List of Person</h6>
                            <div class="row gy-2 gx-4 mb-2">
                                @foreach ($record?->bookingItems ?? [] as $bookingItem)
                                    <div class="col-sm-6">
                                        @if ($bookingItem->entranceFee)
                                            <p class="mb-0" style="font-size: 13px;"><i
                                                    class="fa fa-arrow-right text-primary me-2"></i>{{ $bookingItem->entranceFee?->name }}
                                                -
                                                {{ $bookingItem->entranceFee?->type }} -
                                                ₱{{ $bookingItem->entranceFee?->price }} - X
                                                {{ $bookingItem->quantity }}
                                            </p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            <h6 class="section-title bg-white text-start text-primary pe-3">Room & Cottage</h6>
                            <div class="row gy-2 gx-4 mb-2">
                                @foreach ($record?->bookingItems ?? [] as $bookingItem)
                                    <div class="col-sm-6">
                                        @if ($bookingItem->item)
                                            <p class="mb-0" style="font-size: 13px;"><i
                                                    class="fa fa-arrow-right text-primary me-2"></i>{{ $bookingItem->item?->name }}
                                                -
                                                {{ $bookingItem->item?->type }} -
                                                ₱{{ $bookingItem->item?->price }} - X {{ $bookingItem->quantity }}
                                            </p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <h6 class=" bg-white text-start text-primary pe-3">Total Amount</h6>
                            <div class="row gy-2 gx-4 mb-2">
                                <div class="col-sm-6">
                                    <p class="mb-0" style="font-size: 13px;"><i
                                            class="fa fa-arrow-right text-primary me-2"></i>
                                        ₱ {{ $record->amount_to_pay }}
                                    </p>
                                </div>
                            </div>

                            <h6 class=" bg-white text-start text-primary pe-3">Upload Screenshot of Payment</h6>
                            <div class="row gy-2 gx-4 mb-2">
                                @if ($record->proof_of_payment)
                                @else
                                    <label for="formFile" class="form-label">Upload Image (Required)</label>
                                    <input class="form-control @error('payment_image') is-invalid @enderror"
                                        type="file" id="formFile" accept="image/*" wire:model="payment_image">
                                @endif

                                @error('payment_image')
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
                                    @if ($payment_image)
                                        <img src="{{ $payment_image->temporaryUrl() }}" alt="Payment Preview"
                                            style="max-width: 100%; height: auto;">
                                    @endif

                                    @if ($record->proof_of_payment)
                                        <img src="{{ asset('payments-photo/' . $record->proof_of_payment) }}"
                                            alt="Payment Preview" style="max-width: 100%; height: auto;">
                                    @endif
                                </div>
                            </div>

                            @if ($record->status == 'confirmed')
                                <a href="#" class="btn btn-success py-1 px-3 mt-2 disabled">Confrimed</a>
                            @elseif ($record->proof_of_payment)
                                <a href="#" class="btn btn-warning py-1 px-3 mt-2 disabled">Waiting for
                                    Confirmation</a>
                            @else
                                <a href="#"
                                    class="btn btn-primary py-1 px-3 mt-2 @if (!$payment_image) disabled @endif"
                                    wire:click.prevent="confirm">Confirm Payment</a>
                            @endif
                        </div>
                    </div>
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

            <script>
                const fileInput = document.getElementById('formFile');
                const uploadingIndicator = document.getElementById('uploading-indicator');
                const uploadError = document.getElementById('upload-error');
                const uploadSuccess = document.getElementById('upload-success');
                const imagePreview = document.getElementById('image-preview');

                fileInput.addEventListener('change', handleFileUpload);

                function handleFileUpload() {
                    const file = this.files[0];

                    if (file && file.type.startsWith('image/')) {
                        uploadImage(file);
                    } else if (file) {
                        uploadError.textContent = 'Please upload a valid image file.';
                        uploadError.style.display = 'block';
                        uploadingIndicator.style.display = 'none';
                        uploadSuccess.style.display = 'none';
                        imagePreview.innerHTML = '';
                        this.value = ''; // Clear the input
                    } else {
                        // No file selected
                        uploadingIndicator.style.display = 'none';
                        uploadError.style.display = 'none';
                        uploadSuccess.style.display = 'none';
                        imagePreview.innerHTML = '';
                    }
                }

                function uploadImage(file) {
                    uploadingIndicator.style.display = 'block';
                    uploadError.style.display = 'none';
                    uploadSuccess.style.display = 'none';
                    imagePreview.innerHTML = '';

                    const reader = new FileReader();

                    reader.onload = function(e) {
                        // Display a local preview immediately
                        imagePreview.innerHTML = `<img src="${e.target.result}" style="max-width: 100%; height: auto;">`;

                        // Simulate an upload process (replace with your actual upload logic)
                        setTimeout(() => {
                            uploadingIndicator.style.display = 'none';
                            uploadSuccess.textContent = 'Image uploaded successfully!';
                            uploadSuccess.style.display = 'block';
                            // In a real scenario, you would send the file data to your server here
                        }, 2000); // Simulate 2 seconds of upload time

                        reader.onerror = function(error) {
                            uploadingIndicator.style.display = 'none';
                            uploadError.textContent = 'Error reading the file.';
                            uploadError.style.display = 'block';
                            imagePreview.innerHTML = '';
                            fileInput.value = ''; // Clear the input
                        };
                    };

                    reader.readAsDataURL(file);

                    // In a real application, you would typically use fetch or XMLHttpRequest to send the 'file' to your server.
                    // Example using fetch (you'll need to adapt this to your backend API):
                    /*
                                const formData = new FormData();
                                formData.append('image', file);
                        
                                fetch('/api/upload-image', {
                                    method: 'POST',
                                    body: formData,
                                })
                                .then(response => response.json())
                                .then(data => {
                                    uploadingIndicator.style.display = 'none';
                                    if (data.success) {
                                        uploadSuccess.textContent = data.message || 'Image uploaded successfully!';
                                        uploadSuccess.style.display = 'block';
                                        // Optionally update the image preview with the server URL
                                        // imagePreview.innerHTML = `<img src="${data.imageUrl}" style="max-width: 100%; height: auto;">`;
                                    } else {
                                        uploadError.textContent = data.error || 'Image upload failed.';
                                        uploadError.style.display = 'block';
                                    }
                                })
                                .catch(error => {
                                    uploadingIndicator.style.display = 'none';
                                    uploadError.textContent = 'Network error during upload.';
                                    uploadError.style.display = 'block';
                                });
                                */
                }
            </script>
        </body>
    </div>
</div>
