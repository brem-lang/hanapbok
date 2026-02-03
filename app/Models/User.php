<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Mail\TwoFactorMail;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    // ];

    protected $guarded = [];

    // protected $casts = [
    //     'back_id' => 'array',
    // ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'back_id' => 'array',
            'barangay_clearance' => 'array',
            'waste_management' => 'array',
            'valid_id' => 'array',
            'mayors_permit' => 'array',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isGuest(): bool
    {
        return $this->role === 'guest';
    }

    public function isResortsAdmin(): bool
    {
        return $this->role === 'resorts_admin';
    }

    public function AdminResort()
    {
        return $this->hasOne(Resort::class, 'user_id', 'id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'user_id', 'id');
    }

    public function generateCode()
    {
        $code = rand(100000, 999999);

        UserCodes::updateOrCreate(
            ['user_id' => auth()->user()->id],
            ['code' => $code]
        );

        try {

            $details = [
                'title' => 'Email from HANAPBOK',
                'code' => $code,
                'name' => auth()->user()->name,
            ];

            Mail::to(auth()->user()->email)->send(new TwoFactorMail($details));
        } catch (Exception $e) {
            info('Error: '.$e->getMessage());
        }
    }

    public function userCodes()
    {
        return $this->hasMany(UserCodes::class);
    }
}
