<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'organizer_status',
        'phone',
        'avatar',
        'rejection_reason',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is organizer
     */
    public function isOrganizer(): bool
    {
        return $this->role === 'organizer';
    }

    /**
     * Check if user is regular user
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Check if organizer is approved
     */
    public function isApprovedOrganizer(): bool
    {
        return $this->role === 'organizer' && $this->organizer_status === 'approved';
    }

    /**
     * Check if organizer is pending
     */
    public function isPendingOrganizer(): bool
    {
        return $this->role === 'organizer' && $this->organizer_status === 'pending';
    }

    /**
     * Check if organizer is rejected
     */
    public function isRejectedOrganizer(): bool
    {
        return $this->role === 'organizer' && $this->organizer_status === 'rejected';
    }

    /**
     * Get events created by this user (organizer/admin)
     */
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Get user's bookings
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get user's favorites
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Get user's favorite events
     */
    public function favoriteEvents()
    {
        return $this->belongsToMany(Event::class, 'favorites')->withTimestamps();
    }

    /**
     * Relationship to reviews
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Check if user has favorited an event
     */
    public function hasFavorited(Event $event): bool
    {
        return $this->favorites()->where('event_id', $event->id)->exists();
    }
}
