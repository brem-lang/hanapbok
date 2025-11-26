<?php

namespace App\Livewire;

use App\Filament\Resources\LostItemResource;
use App\Models\LostItem;
use App\Models\Resort;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Session;

class ReportLostItems extends Component
{
    use WithFileUploads;

    public $record;

    public $description;

    public $location;

    public $date;

    public $selectResort;

    public $resorts;

    public $uploadPhoto;

    public $type;

    public $activePage = 'list';

    public $notifications;

    public $unreadNotificationsCount;

    public function mount()
    {
        $this->resorts = Resort::get();

        $this->record = LostItem::with('resort')
            ->where('user_id', auth()->user()->id)
            ->orWhere('user_id', null)
            ->latest()->get();

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
        return view('livewire.report-lost-items');
    }

    public function report()
    {
        $this->dispatch('close-modal');

        $this->validate([
            'description' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'date' => 'required|date',
            'selectResort' => 'required|exists:resorts,id',
            'uploadPhoto' => 'image|max:2048',
            'type' => 'required|string|max:255',
        ]);

        $path = $this->uploadPhoto->store('', 'public_uploads_lost_item');

        $lostItem = LostItem::create([
            'user_id' => auth()->user()->id,
            'description' => $this->description,
            'location' => $this->location,
            'date' => $this->date,
            'resort_id' => $this->selectResort,
            'photo' => $path,
            'status' => $this->type == 'lost_item' ? 'not_found' : 'not_claimed',
            'type' => $this->type,
        ]);

        $this->dispatch('swal:modal');

        $selectedResor = Resort::find($this->selectResort);

        Notification::make()
            ->success()
            ->title('Report Submitted')
            ->icon('heroicon-o-check-circle')
            ->body(auth()->user()->name.' has submitted a report.')
            ->actions([
                Action::make('view')
                    ->label('View')
                    ->url(fn () => LostItemResource::getUrl('edit', ['record' => $lostItem->id]))
                    ->markAsRead(),
            ])
            ->sendToDatabase(User::where('id', $selectedResor->userAdmin->id)->get());

        return redirect('/lost-items');
    }

    public function createReport()
    {
        $this->activePage = 'create';
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->to('/');
    }
}
