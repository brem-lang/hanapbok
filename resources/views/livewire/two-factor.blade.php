<div class="min-h-screen bg-gray-50 text-gray-900 dark:bg-gray-950 dark:text-white font-[Figtree] flex items-center justify-center px-4"
    style="background-image: url('{{ asset('img/565800896_1564868748015658_5418465562393476186_n.jpg') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="card p-4 shadow" style="max-width: 400px; width: 100%;">
        <div class="text-center mb-3">
            {{-- <img src="{{ asset('img/logo1.png') }}" alt="Logo"
             class="mb-3 d-block mx-auto" style="height: 90px;"> --}}
            <h4 class="mb-1">Two Factor Authentication</h4>
            <p class="text-muted">Please check your email and enter the code below.</p>
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
