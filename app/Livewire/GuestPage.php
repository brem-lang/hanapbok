<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Session;

class GuestPage extends Component
{
    public $notifications;

    public $unreadNotificationsCount;

    public function render()
    {
        return view('livewire.guest-page');
    }

    public function mount()
    {
        if (Auth::check()) {

            if (! auth()->user()->isGuest() && ! Session::has('user_2fa')) {
                abort(404);
            }

            // if (! Session::has('user_2fa')) {
            //     abort(404);
            // }

            $this->loadNotifications();

            $review = auth()->user()->bookings()->where('is_review', true)->first();
            if ($review) {
                return redirect()->route('review', $review->resort_id);
            }
        }
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return redirect()->route('index');
    }

    public function clearAll()
    {
        auth()->user()->notifications()->delete();

        return redirect()->route('index');
    }

    public function loadNotifications()
    {
        $this->notifications = auth()->user()
            ->notifications()
            ->take(50)
            ->get();

        $this->unreadNotificationsCount = auth()->user()->unreadNotifications->count();
    }

    public function login()
    {
        return redirect()->to('/app/login');
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->to('/');
    }
}
