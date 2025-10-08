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
                {{-- <a class="nav-item nav-link active">Book Now</a>
                <a href="{{ route('my-bookings') }}" class="nav-item nav-link">My Bookings</a>
                <a href="{{ route('lost-items') }}" class="nav-item nav-link">Lost and Found Items</a> --}}

                {{-- <a class="nav-item nav-link position-relative">
                    <i class="fa fa-bell fs-5"></i>
                    <span class="position-absolute translate-middle badge rounded-pill bg-danger">
                        3
                        <span class="visually-hidden">unread messages</span>
                    </span>
                </a> --}}
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
                {{-- <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center">
                        <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('guest-booking') }}">Resorts</a></li>
                        <li class="breadcrumb-item text-white active" aria-current="page">
                            {{ $record->name }}</li>
                    </ol>
                </nav> --}}
            </div>
        </div>
    </div>
    <!-- Navbar & Hero End -->


    <div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container">
            <div class="booking p-5">
                <div class="row g-5 align-items-center">
                    <div class="col-md-6 text-white">
                        {{-- <h6 class="text-white text-uppercase">Booking</h6> --}}
                        <h1 class="text-white mb-4">Resort Review</h1>
                        {{-- <p class="mb-4">{{ $record->description }}</p> --}}
                        {{-- <a class="btn btn-outline-light py-3 px-5 mt-2" href=""
                            wire:click.prevent="viewInformation">View
                            Information</a> --}}
                    </div>
                    <div class="col-md-6">
                        <div class="mt-4">
                            <div>
                                <div class="form-floating">
                                    <textarea class="form-control" wire:model="review" required style="height: 200px; background-color: #fff;"></textarea>
                                    <label for="review">Review</label>
                                </div>
                                @error('review')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="form-label d-block">Rating</label>
                            {{-- <div id="star-rating" class="fs-4">
                                <i class="fa fa-star text-secondary" data-value="1"></i>
                                <i class="fa fa-star text-secondary" data-value="2"></i>
                                <i class="fa fa-star text-secondary" data-value="3"></i>
                                <i class="fa fa-star text-secondary" data-value="4"></i>
                                <i class="fa fa-star text-secondary" data-value="5"></i>
                            </div>
                            <input type="hidden" name="rating" wire:model.live='rating' id="rating" value=""> --}}
                            <div class="mb-3 d-flex gap-1">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="fa fa-star {{ $rating >= $i ? 'text-secondary' : 'text-muted' }}"
                                        style="cursor: pointer; font-size: 1.5rem;"
                                        wire:click="$set('rating', {{ $i }})"></i>
                                @endfor
                            </div>

                            <input type="hidden" wire:model="rating" id="rating">
                            @error('rating')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <div class="col-12 mt-4">
                                {{-- <button class="btn btn-outline-light w-100 py-3"
                                            wire:click.prevent='submit'>Book
                                            Now</button> --}}
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-outline-light w-100 py-3"
                                    wire:click.prevent='submit'>
                                    Submit
                                </button>

                                <!-- Modal -->
                                {{-- <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static"
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
                                                <button type="button" wire:click.prevent='submit'
                                                    class="btn btn-primary">Confirm</button>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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

{{-- <script>
    window.addEventListener('close-modal', () => {
        const modalEl = document.getElementById('staticBackdrop');
        const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
        modal.hide();
    });
</script> --}}

{{-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        const stars = document.querySelectorAll('#star-rating i');
        const ratingInput = document.getElementById('rating');

        stars.forEach(star => {
            star.addEventListener('click', () => {
                const value = star.getAttribute('data-value');
                ratingInput.value = value;

                stars.forEach(s => {
                    s.classList.remove('text-primary');
                    s.classList.add('text-secondary');
                    if (s.getAttribute('data-value') <= value) {
                        s.classList.add('text-primary');
                        s.classList.remove('text-secondary');
                    }
                });
            });

            star.addEventListener('mouseover', () => {
                const value = star.getAttribute('data-value');
                stars.forEach(s => {
                    s.classList.toggle('text-primary', s.getAttribute('data-value') <=
                        value);
                    s.classList.toggle('text-secondary', s.getAttribute('data-value') >
                        value);
                });
            });

            star.addEventListener('mouseout', () => {
                const value = ratingInput.value;
                stars.forEach(s => {
                    s.classList.toggle('text-primary', s.getAttribute('data-value') <=
                        value);
                    s.classList.toggle('text-secondary', s.getAttribute('data-value') >
                        value);
                });
            });
        });
    });
</script> --}}
