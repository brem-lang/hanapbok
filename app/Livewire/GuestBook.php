<?php

namespace App\Livewire;

use App\Models\Resort;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Session;

class GuestBook extends Component implements HasForms
{
    public $notifications;

    public $unreadNotificationsCount;

    use InteractsWithForms;

    public $search = '';

    public $resorts;

    public function mount()
    {
        $this->resorts = Resort::where('is_active', true)->get();

        if (Auth::check()) {
            if (! auth()->user()->isGuest()) {
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

    public function render()
    {
        return view('livewire.guest-book');
    }

    public function searchResorts()
    {
        $this->resorts = Resort::where('is_active', true)->when(
            $this->search,
            function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%');
            }
        )->get();
    }

    public function bookResort($id)
    {

        if (Auth::check()) {
            return redirect()->route('view-resort', $id);
        } else {
            return redirect('/app/register');
        }

        // return redirect()->route('view-resort', $id);
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
