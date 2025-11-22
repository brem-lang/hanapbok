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

    public function mount()
    {
        // Initialize empty otp array
        $this->otp = array_fill(0, 6, '');
    }

    public function render(): View
    {
        return view('livewire.two-factor');
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

            UserCodes::updateOrCreate(
                ['user_id' => auth()->user()->id],
                ['code' => $code]
            );

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
