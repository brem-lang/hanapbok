<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProFile extends Component
{
    use WithFileUploads;

    public $notifications;

    public $unreadNotificationsCount;

    public $name;

    public $email;

    public $phone;

    public $frontId;

    public $backId;

    public $frontIdPreview;

    public $backIdPreview;

    public function mount()
    {
        if (Auth::check()) {
            $this->loadNotifications();

            $review = auth()->user()->bookings()->where('is_review', true)->first();
            if ($review) {
                return redirect()->route('review', $review->resort_id);
            }
            $user = auth()->user();

            $this->name = $user->name;
            $this->email = $user->email;
            $this->phone = $user->contact_number;
            $this->frontIdPreview = $user->front_id;
            $this->backIdPreview = $user->back_id;
        }
    }

    public function loadNotifications()
    {
        $this->notifications = auth()->user()
            ->notifications()
            ->take(50)
            ->get();

        $this->unreadNotificationsCount = auth()->user()->unreadNotifications->count();
    }

    public function updateProfile()
    {
        $user = auth()->user();

        $user->update([
            'contact_number' => $this->phone,
        ]);

        session()->flash('success', 'Profile updated successfully!');
    }

    public function render()
    {
        return view('livewire.pro-file');
    }
}
