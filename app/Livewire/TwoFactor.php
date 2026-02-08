<?php

namespace App\Livewire;

use App\Mail\TwoFactorMail;
use App\Models\UserCodes;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Exception;
use Filament\Facades\Filament;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Session;

class TwoFactor extends Component
{
    use WithRateLimiting;

    public $otp = [];

    protected $rules = [
        'otp.0' => 'required|numeric',
        'otp.1' => 'required|numeric',
        'otp.2' => 'required|numeric',
        'otp.3' => 'required|numeric',
        'otp.4' => 'required|numeric',
        'otp.5' => 'required|numeric',
    ];

    public $otpExpiryTime = null;

    public function mount()
    {
        // Initialize empty otp array
        $this->otp = array_fill(0, 6, '');

        // Fetch the current OTP's updated_at timestamp
        $userCode = UserCodes::where('user_id', auth()->user()->id)->first();
        if ($userCode) {
            // Calculate expiry time: updated_at + 2 minutes
            $this->otpExpiryTime = $userCode->updated_at->copy()->addMinutes(2)->timestamp * 1000; // Convert to milliseconds for JavaScript
        }
    }

    public function render(): View
    {
        return view('livewire.two-factor', [
            'otpExpiryTime' => $this->otpExpiryTime,
        ]);
    }

    public function submit()
    {
        $this->validate();

        try {
            $this->rateLimit(5);

            $find = UserCodes::where('user_id', auth()->user()->id)
                ->where('code', implode('', $this->otp))
                ->where('updated_at', '>=', now()->subMinutes(2))
                ->first();

            if (! is_null($find)) {
                Session::put('user_2fa', auth()->user()->id);

                auth()->user()->update([
                    'is_2fa' => true,
                ]);

                redirect('index');
                // if (auth()->user()->isGuest()) {
                //     redirect('index');
                // } else {
                //     redirect()->intended(Filament::getUrl());
                // }

                // return redirect()->route('policy.index');
            } else {
                $this->dispatch('swal:warning', [
                    'title' => 'Invalid OTP!',
                    'text' => 'Please enter the correct OTP code.',
                    'icon' => 'warning',
                ]);
            }
        } catch (TooManyRequestsException $exception) {
            $this->dispatch('swal:warning', [
                'title' => 'Too many requests!',
                'text' => 'Please try again later.',
                'icon' => 'warning',
            ]);
        }
    }

    public function resend()
    {
        try {
            $this->rateLimit(5);

            $code = rand(100000, 999999);

            $userCode = UserCodes::updateOrCreate(
                ['user_id' => auth()->user()->id],
                ['code' => $code]
            );

            // Update expiry time for the new OTP
            $this->otpExpiryTime = $userCode->updated_at->copy()->addMinutes(2)->timestamp * 1000;

            // Dispatch event to reset timer on frontend (OTP is created, so timer should reset)
            $this->dispatch('otp-resent', [
                'expiryTime' => $this->otpExpiryTime,
            ]);

            try {
                $details = [
                    'title' => 'Mail from NetlinkVoice.com',
                    'code' => $code,
                    'name' => auth()->user()->name,
                ];

                Mail::to(auth()->user()->email)->send(new TwoFactorMail($details));

                $this->dispatch('swal:success', [
                    'title' => 'OTP Sent!',
                    'text' => 'Check your email for the new OTP code.',
                    'icon' => 'success',
                ]);
            } catch (Exception $e) {
                logger('Error: '.$e->getMessage());
            }
        } catch (TooManyRequestsException $exception) {
            $this->dispatch('swal:warning', [
                'title' => 'Too many requests!',
                'text' => 'Please try again later.',
                'icon' => 'warning',
            ]);
        }
    }
}
