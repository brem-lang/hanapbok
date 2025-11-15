<?php

namespace App\Livewire;

use App\Models\GuestReview;
use App\Models\Resort;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Session;

class Review extends Component
{
    public $record;

    public $review;

    public $rating;

    public function mount($id)
    {
        if (! auth()->user()->isGuest()) {
            abort(404);
        }

        if (! Session::has('user_2fa')) {
            abort(404);
        }

        $review = auth()->user()->bookings()->where('is_review', true)->first();

        if (! $review) {
            abort(404);
        }

        $this->record = Resort::with('items', 'entranceFees', 'userAdmin')->find($id);
    }

    public function submit()
    {
        $this->validate([
            'review' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        GuestReview::create([
            'user_id' => auth()->user()->id,
            'resort_id' => $this->record->id,
            'review' => $this->review,
            'rating' => $this->rating,
        ]);

        auth()->user()->bookings()->where('is_review', true)->first()->update([
            'is_review' => false,
        ]);

        return redirect()->to('/index');
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->to('/');
    }

    public function render()
    {
        return view('livewire.review');
    }
}
