<div class="min-h-screen bg-gray-50 text-gray-900 dark:bg-gray-950 dark:text-white font-[Figtree] flex items-center justify-center px-4"
    style="background-image: url('{{ asset('img/565800896_1564868748015658_5418465562393476186_n.jpg') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="card p-4 shadow" style="max-width: 400px; width: 100%;">
        <div class="text-center mb-3">
            {{-- <img src="{{ asset('img/logo1.png') }}" alt="Logo"
             class="mb-3 d-block mx-auto" style="height: 90px;"> --}}
            <h4 class="mb-1">Two Factor Authentication</h4>
            <p class="text-muted">Please check your email and enter the code below.</p>
            <div id="otp-timer" class="mt-2 mb-2" style="font-size: 1rem; font-weight: 600;">
                <span id="timer-display" class="text-primary">Time remaining: 2:00</span>
                <span id="timer-expired" class="text-danger" style="display: none;">OTP expired</span>
            </div>
        </div>

        <form wire:submit.prevent="submit">
            <div class="d-flex justify-content-between mb-3">
                @for ($i = 0; $i < 6; $i++)
                    <input type="text" maxlength="1" pattern="\d*" class="form-control text-center otp-input"
                        wire:model.defer="otp.{{ $i }}"
                        style="width: 50px; height: 50px; font-size: 1.5rem;">
                @endfor
            </div>

            @error('otp')
                <div class="text-danger small mb-2">{{ $message }}</div>
            @enderror

            <button type="submit" class="btn btn-primary w-100">Confirm</button>

            <button type="button" class="btn btn-outline-secondary w-100 mt-3" wire:click="resend">
                Resend OTP
            </button>
        </form>
    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const inputs = document.querySelectorAll(".otp-input");

        inputs.forEach((input, index) => {
            input.addEventListener("input", function() {
                this.value = this.value.replace(/[^0-9]/g, "");

                if (this.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });

            input.addEventListener("keydown", function(e) {
                if (e.key === "Backspace" && this.value === "" && index > 0) {
                    inputs[index - 1].focus();
                }
            });
        });

        // OTP Countdown Timer
        let otpTimerInterval = null;
        const timerDisplay = document.getElementById('timer-display');
        const timerExpired = document.getElementById('timer-expired');
        const otpExpiryTime = @json($otpExpiryTime);

        function startTimer(expiryTimestamp) {
            // Clear any existing timer
            if (otpTimerInterval) {
                clearInterval(otpTimerInterval);
            }

            function updateTimer() {
                const now = Date.now();
                const remaining = expiryTimestamp - now;

                if (remaining <= 0) {
                    // Timer expired
                    timerDisplay.style.display = 'none';
                    timerExpired.style.display = 'inline';
                    clearInterval(otpTimerInterval);
                    otpTimerInterval = null;
                } else {
                    // Calculate minutes and seconds
                    const minutes = Math.floor(remaining / 60000);
                    const seconds = Math.floor((remaining % 60000) / 1000);
                    const formattedTime = `${minutes}:${seconds.toString().padStart(2, '0')}`;
                    timerDisplay.textContent = `Time remaining: ${formattedTime}`;
                    timerDisplay.style.display = 'inline';
                    timerExpired.style.display = 'none';
                }
            }

            // Update immediately
            updateTimer();

            // Update every second
            otpTimerInterval = setInterval(updateTimer, 1000);
        }

        // Start timer if expiry time is available
        if (otpExpiryTime) {
            startTimer(otpExpiryTime);
        }

        // Listen for OTP resent event from Livewire
        window.addEventListener('otp-resent', function(event) {
            // Livewire dispatches events with data in event.detail array
            const data = Array.isArray(event.detail) ? event.detail[0] : event.detail;
            if (data && data.expiryTime) {
                startTimer(data.expiryTime);
            }
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        window.addEventListener('swal:success', function(event) {
            // console.log("SWAL EVENT RECEIVED:", event.detail);

            const data = event.detail[0];

            Swal.fire({
                title: data.title,
                text: data.text,
                icon: data.icon,
                toast: true,
                position: 'top-end',
                timer: 3000,
                showConfirmButton: false,
            });
        });

        document.addEventListener("swal:warning", function(event) {
            const data = event.detail[0];

            Swal.fire({
                title: data.title,
                text: data.text,
                icon: 'warning',
                toast: true,
                position: "top-end",
                timer: 3000,
                showConfirmButton: false,
            });
        });
    });
</script>
