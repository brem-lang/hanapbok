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
                <a href="{{ route('lost-items') }}" class="nav-item nav-link">Lost and Found Items</a>

                {{-- <a class="nav-item nav-link position-relative">
                    <i class="fa fa-bell fs-5"></i>
                    <span class="position-absolute translate-middle badge rounded-pill bg-danger">
                        3
                        <span class="visually-hidden">unread messages</span>
                    </span>
                </a> --}}
            </div>
            <a href="" class="btn btn-primary py-2 px-4" wire:click.prevent="logout">Logout</a>
        </nav>

        {{-- <div class="container-fluid bg-primary py-5 mb-5 hero-header">
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
                </div>
                {{-- <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.3s">
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
                                ₱ {{ $record->amount_to_pay ?? '0' }}
                            </p>
                        </div>
                    </div>

                    <h6 class=" bg-white text-start text-primary pe-3">Amount Paid</h6>
                    <div class="row gy-2 gx-4 mb-2">
                        <div class="col-sm-6">
                            <p class="mb-0" style="font-size: 13px;"><i
                                    class="fa fa-arrow-right text-primary me-2"></i>
                                ₱ {{ $record->amount_paid ?? '0' }}
                            </p>
                        </div>
                    </div>

                    <h6 class=" bg-white text-start text-primary pe-3">Balance</h6>
                    <div class="row gy-2 gx-4 mb-2">
                        <div class="col-sm-6">
                            <p class="mb-0" style="font-size: 13px;"><i
                                    class="fa fa-arrow-right text-primary me-2"></i>
                                ₱ {{ $record->balance ?? '0' }}
                            </p>
                        </div>
                    </div>

                    <h6 class=" bg-white text-start text-primary pe-3">Upload Screenshot of Payment</h6>
                    <div class="row gy-2 gx-4 mb-2">
                        @if ($record->proof_of_payment)
                        @else
                            <label for="formFile" class="form-label">Upload Image (Required)</label>
                            <input class="form-control @error('payment_image') is-invalid @enderror" type="file"
                                id="formFile" accept="image/*" wire:model="payment_image">
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

                    <h6 class=" bg-white text-start text-primary pe-3">Date</h6>
                    <div class="row gy-2 gx-4 mb-2">
                        <div class="col-sm-6">
                            <p class="mb-0" style="font-size: 13px;"><i
                                    class="fa fa-arrow-right text-primary me-2"></i>
                                {{ \Carbon\Carbon::parse($record->date)->format('F j, Y h:i A') }}
                                ---
                                {{ \Carbon\Carbon::parse($record->date_to)->format('F j, Y h:i A') }}
                            </p>
                        </div>
                    </div>

                    <h6 class=" bg-white text-start text-primary pe-3">Payment Type</h6>
                    <div class="row gy-2 gx-4 mb-2">
                        <div class="col-sm-6">
                            <p class="mb-0" style="font-size: 13px;"><i
                                    class="fa fa-arrow-right text-primary me-2"></i>
                                {{ $record->payment_type == 'gcash' ? 'GCash' : 'Walk In' }}

                                @if ($record->payment_type == 'walk_in')
                                    <br>
                                    <p style="font-size: 12px;">To secure your booking, please make a partial payment.
                                    </p>
                                @endif
                            </p>
                        </div>
                    </div>

                    @if ($record->status == 'cancelled')
                        <h6 class=" bg-white text-start text-primary pe-3">Cancel Reason</h6>
                        <div class="row gy-2 gx-4 mb-2">
                            <div class="col-sm-6">
                                <p class="mb-0" style="font-size: 13px;"><i
                                        class="fa fa-arrow-right text-primary me-2"></i>
                                    {{ $record->cancel_reason }}
                                </p>
                            </div>
                        </div>
                    @endif


                    @if ($record->status == 'confirmed')
                        <a href="#" class="btn btn-success py-1 px-3 mt-2 disabled">Confrim</a>
                    @elseif ($record->proof_of_payment)
                        <a href="#" class="btn btn-warning py-1 px-3 mt-2 disabled">Waiting for
                            Confirmation</a>
                    @else
                        <button type="button"
                            class="btn btn-primary py-1 px-3 mt-2 @if (!$payment_image) disabled @endif"
                            data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                            Confirm Payment
                        </button>

                        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static"
                            data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                            aria-hidden="true" wire:ignore.self>
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

                                        <button type="button" wire:click.prevent="confirm"
                                            wire:loading.attr="disabled" class="btn btn-primary"
                                            data-bs-dismiss="modal">
                                            <span wire:loading.remove>Confirm</span>
                                            <span wire:loading>Processing...</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="row gy-2 gx-4 mb-2" style="margin-top: 10px;">
                        @if ($record->is_partial)
                            You paid partial amount pls pay full amount on upcoming check in
                        @endif
                    </div>
                </div> --}}
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.3s">

                    {{-- === 1. ITEMS BOOKED TABLE === --}}
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0">Items Booked & Guests</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped table-sm mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th scope="col" style="width: 55%;">Description</th>
                                            <th scope="col" style="width: 15%;">Type</th>
                                            <th scope="col" class="text-end" style="width: 15%;">Price</th>
                                            <th scope="col" class="text-center" style="width: 15%;">Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        {{-- Room & Cottage Items --}}
                                        @foreach ($record?->bookingItems ?? [] as $bookingItem)
                                            @if ($bookingItem->item)
                                                <tr>
                                                    <td><i class="fa fa-home text-primary me-2"></i>
                                                        {{ $bookingItem->item?->name }}</td>
                                                    <td>{{ $bookingItem->item?->type }}</td>
                                                    <td class="text-end">
                                                        ₱{{ number_format($bookingItem->item?->price, 2) }}</td>
                                                    <td class="text-center">{{ $bookingItem->quantity }}</td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        {{-- Entrance Fee / Person Items --}}
                                        @foreach ($record?->bookingItems ?? [] as $bookingItem)
                                            @if ($bookingItem->entranceFee)
                                                <tr>
                                                    <td><i class="fa fa-user-plus text-primary me-2"></i>
                                                        {{ $bookingItem->entranceFee?->name }}</td>
                                                    <td>{{ $bookingItem->entranceFee?->type }}</td>
                                                    <td class="text-end">
                                                        ₱{{ number_format($bookingItem->entranceFee?->price, 2) }}</td>
                                                    <td class="text-center">{{ $bookingItem->quantity }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                    <div class="card mb-4 shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0">Extra Services & Additional Charges</h6>
                        </div>
                        <div class="card-body p-0">
                            @php
                                // FIX: Access the attribute directly. Laravel automatically decodes the JSON column.
                                $additionalCharges = $record->additional_charges ?? [];
                                // Ensure it's an array for iteration
if (!is_array($additionalCharges)) {
    $additionalCharges = [];
}

$totalChargesSum = collect($additionalCharges)->sum('total_charges');
                            @endphp

                            @if (!empty($additionalCharges))
                                <div class="table-responsive">
                                    <table class="table table-striped table-sm mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th scope="col" style="width: 55%;">Charge Name / ID</th>
                                                <th scope="col" class="text-end" style="width: 15%;">Unit Price
                                                </th>
                                                <th scope="col" class="text-center" style="width: 15%;">Qty</th>
                                                <th scope="col" class="text-end fw-bold" style="width: 15%;">
                                                    Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($additionalCharges as $charge)
                                                <tr class="table-light">
                                                    <td><i class="fa fa-tag me-2"></i>
                                                        Charge ID:
                                                        {{ $charge['charge_id'] }}
                                                    </td>
                                                    <td class="text-end">
                                                        ₱{{ number_format($charge['amount'] ?? 0, 2) }}</td>
                                                    <td class="text-center">{{ $charge['quantity'] ?? 0 }}</td>
                                                    <td class="text-end fw-bold">
                                                        ₱{{ number_format($charge['total_charges'] ?? 0, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>

                                        {{-- FOOTER ROW FOR TOTAL SUM --}}
                                        <tfoot>
                                            <tr class="bg-primary text-white fw-bolder">
                                                <td colspan="3" class="text-end">Total Additional Charges:</td>
                                                <td class="text-end">₱{{ number_format($totalChargesSum, 2) }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            @else
                                <div class="p-3 text-muted fst-italic">No additional charges were applied to this
                                    booking.</div>
                            @endif
                        </div>
                    </div>

                    {{-- === 2. BOOKING AND PAYMENT SUMMARY === --}}

                    @if ($record->status == 'completed')
                        <div class="card mb-4 shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">Summary & Payment Status</h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="row">

                                    {{-- Date and Payment Type Column --}}
                                    <div class="col-md-6 border-end">
                                        <p class="fw-bold mb-1">Booking Period:</p>
                                        <p class="mb-2" style="font-size: 13px;">
                                            <i class="fa fa-calendar-alt text-primary me-2"></i>
                                            {{ \Carbon\Carbon::parse($record->date)->format('F j, Y h:i A') }}
                                            to
                                            <br>
                                            <i class="fa fa-calendar-alt text-primary me-2"></i>
                                            {{ \Carbon\Carbon::parse($record->date_to)->format('F j, Y h:i A') }}
                                        </p>
                                        <p class="fw-bold mb-1">Payment Method:</p>
                                        <p class="mb-0" style="font-size: 13px;">
                                            <i class="fa fa-credit-card text-primary me-2"></i>
                                            <span
                                                class="fw-bold">{{ $record->payment_type == 'gcash' ? 'GCash' : 'Walk In' }}</span>
                                            @if ($record->payment_type == 'walk_in')
                                                <br><small class="text-muted fst-italic">To secure your booking, please
                                                    make a partial payment.</small>
                                            @endif
                                        </p>
                                    </div>

                                    {{-- Financial Summary Column --}}
                                    <div class="col-md-6">
                                        <table class="table table-borderless table-sm mb-0">
                                            <tbody>
                                                <tr>
                                                    <td class="fw-bold text-end">Total Amount:</td>
                                                    <td class="text-end text-dark fw-bold">₱
                                                        {{ number_format($record->amount_paid ?? 0, 2) }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold text-end">Booking Amount:</td>
                                                    <td class="text-end text-dark fw-bold">₱
                                                        {{ number_format($record->amount_to_pay ?? 0, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold text-end text-success">Down Payment:</td>
                                                    <td class="text-end text-success fw-bold">₱
                                                        {{ number_format($record->amount_send ?? 0, 2) }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold text-end text-danger">Balance Due:</td>
                                                    <td class="text-end text-danger fw-bolder">₱
                                                        {{ number_format($record->balance ?? 0, 2) }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        @if ($record->is_partial)
                                            <div class="alert alert-warning p-2 mt-2" role="alert"
                                                style="font-size: 12px;">
                                                You paid a partial amount. Please pay the full balance on or before
                                                check-in.
                                            </div>
                                        @endif
                                        @if ($record->status == 'cancelled')
                                            <p class="fw-bold mb-1 mt-3 text-danger">Cancel Reason:</p>
                                            <p class="mb-0 text-danger" style="font-size: 13px;">
                                                {{ $record->cancel_reason }}</p>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- === 3. PAYMENT PROOF & ACTIONS === --}}
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 text-primary">Upload Payment Screenshot</h6>
                        </div>
                        <div class="card-body">
                            @if ($record->proof_of_payment)
                                <p class="text-success fw-bold">Proof of Payment is already submitted.</p>
                                <img src="{{ asset('payments-photo/' . $record->proof_of_payment) }}"
                                    alt="Submitted Payment Proof" class="img-fluid rounded border p-1"
                                    style="max-width: 300px; height: auto;">
                            @else
                                <label for="formFile" class="form-label fw-bold">Upload Image (Required)</label>
                                <input class="form-control @error('payment_image') is-invalid @enderror"
                                    type="file" id="formFile" accept="image/*" wire:model="payment_image">

                                @error('payment_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <div id="image-preview" class="mt-3">
                                    @if ($payment_image)
                                        <img src="{{ $payment_image->temporaryUrl() }}" alt="Payment Preview"
                                            class="img-fluid rounded" style="max-width: 300px; height: auto;">
                                    @endif
                                </div>
                                <div class="row g-3">

                                    {{-- 1. Reference Number Input (Transaction ID) --}}
                                    <div class="col-md-6 mb-3">
                                        <label for="referenceNumberInput" class="form-label fw-bold">Reference
                                            Number</label>
                                        <input class="form-control @error('reference_number') is-invalid @enderror"
                                            type="text" id="referenceNumberInput"
                                            wire:model.live="reference_number"
                                            placeholder="e.g., GCash Transaction ID">

                                        @error('reference_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- 2. Amount of Money Sent Input --}}
                                    <div class="col-md-6 mb-3">
                                        <label for="amountSentInput" class="form-label fw-bold">Amount Sent</label>
                                        <input class="form-control @error('amount_sent') is-invalid @enderror"
                                            type="number" id="amountSentInput" wire:model.live="amount_sent"
                                            placeholder="0.00" step="0.01" min="1">

                                        @error('amount_sent')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                </div>

                                {{-- Action Buttons --}}
                                <div class="mt-4">
                                    @if ($record->status == 'confirmed')
                                        <a href="#"
                                            class="btn btn-success py-1 px-3 mt-2 disabled">Confirmed</a>
                                    @elseif ($record->proof_of_payment)
                                        <a href="#" class="btn btn-warning py-1 px-3 mt-2 disabled">Waiting for
                                            Confirmation</a>
                                    @else
                                        {{-- The button now triggers the modal for final confirmation --}}
                                        <button type="button"
                                            class="btn btn-primary py-1 px-3 mt-2 @if (!$payment_image) disabled @endif"
                                            data-bs-toggle="modal" data-bs-target="#staticBackdrop">

                                            Confirm Payment

                                        </button>

                                        {{-- MODAL STRUCTURE (Needs to be defined outside this component or included) --}}
                                        {{-- Since the modal structure was provided, I'll assume it's included elsewhere --}}
                                        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static"
                                            data-bs-keyboard="false" tabindex="-1"
                                            aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Confirm
                                                            Payment</h1>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">Are you sure you want to submit this proof
                                                        of payment?</div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="button" wire:click.prevent="confirm"
                                                            class="btn btn-primary"
                                                            data-bs-dismiss="modal">Confirm</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s" style="min-height: 400px;">
                    <div class="position-relative h-100">
                        <h6 class=" bg-white text-start text-primary pe-3">Scan QR Code</h6>
                        <h3>{{ $record->resort['userAdmin']['contact_number'] }} -
                            {{ $record->resort['userAdmin']['name'] }}</h3>
                        <h6>
                            50% Deposit Required ₱ {{ number_format($record->amount_to_pay / 2, 2) }}
                        </h6>
                        <img class="img-fluid position-absolute w-100 h-100"
                            src="{{ asset('qr-photo/' . $record->resort->qr) }}" alt=""
                            style="object-fit: cover;">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="container-xxl py-5">
        <div class="container">
            <h6 class=" bg-white text-start text-primary pe-3">Scan QR Code</h6>
            <img class="img-fluid position-absolute" src="{{ asset('img/qrcode.svg') }}" alt=""
                style="object-fit: cover;">
        </div>
    </div> --}}

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

        }
    </script>
</div>
