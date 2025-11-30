<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'booking_code',
        'total_amount',
        'status',
        'approved_at',
        'cancelled_at',
        'cancellation_deadline',
        'notes',
        'cancellation_reason',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'approved_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'cancellation_deadline' => 'datetime',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (empty($booking->booking_code)) {
                $booking->booking_code = self::generateBookingCode();
            }
        });
    }

    /**
     * Generate unique booking code
     */
    public static function generateBookingCode(): string
    {
        do {
            $code = 'EVT-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
        } while (self::where('booking_code', $code)->exists());

        return $code;
    }

    /**
     * Get the user who made this booking
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get booking details
     */
    public function details()
    {
        return $this->hasMany(BookingDetail::class);
    }

    /**
     * Alias for details
     */
    public function bookingDetails()
    {
        return $this->hasMany(BookingDetail::class);
    }

    /**
     * Get the event through booking details
     */
    public function event()
    {
        return $this->hasOneThrough(
            Event::class,
            BookingDetail::class,
            'booking_id',
            'id',
            'id',
            'ticket_id'
        )->join('tickets', 'tickets.id', '=', 'booking_details.ticket_id')
         ->select('events.*');
    }

    /**
     * Get all events in this booking
     */
    public function events()
    {
        $ticketIds = $this->details()->pluck('ticket_id');
        $eventIds = Ticket::whereIn('id', $ticketIds)->pluck('event_id');
        return Event::whereIn('id', $eventIds)->get();
    }

    /**
     * Relationship to review
     */
    public function review()
    {
        return $this->hasOne(Review::class);
    }

    /**
     * Check if booking can be reviewed
     */
    public function canBeReviewed(): bool
    {
        // Harus approved
        if ($this->status !== 'approved') {
            return false;
        }

        // Belum pernah review
        if ($this->review) {
            return false;
        }

        // Event sudah selesai
        $event = $this->details->first()->ticket->event ?? null;
        if ($event) {
            return $event->date_start->isPast();
        }

        return false;
    }

    /**
     * Check if booking is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if booking is approved
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if booking is cancelled
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Check if booking can be cancelled by user
     */
    public function canBeCancelled(): bool
    {
        // Hanya bisa cancel jika status pending
        // Atau approved tapi event belum mulai (minimal H-1)
        if ($this->status === 'cancelled') {
            return false;
        }

        if ($this->status === 'pending') {
            return true;
        }

        if ($this->status === 'approved') {
            // Ambil event dari booking
            $event = $this->details->first()->ticket->event ?? null;
            if ($event) {
                // Bisa cancel jika event masih lebih dari 1 hari lagi
                return $event->date_start->isAfter(now()->addDay());
            }
        }

        return false;
    }

    /**
     * Get cancellation deadline
     */
    public function getCancellationDeadlineAttribute()
    {
        $event = $this->details->first()->ticket->event ?? null;
        if ($event) {
            return $event->date_start->subDay();
        }
        return null;
    }

    /**
     * Approve the booking
     */
    public function approve(): bool
    {
        $this->status = 'approved';
        $this->approved_at = now();
        return $this->save();
    }

    /**
     * Cancel the booking
     */
    public function cancel(?string $reason = null): bool
    {
        // Restore ticket quantities
        foreach ($this->details as $detail) {
            $detail->ticket->decrement('sold', $detail->quantity);
        }

        $this->status = 'cancelled';
        $this->cancelled_at = now();
        $this->cancellation_reason = $reason;
        return $this->save();
    }

    /**
     * Get total tickets count
     */
    public function getTotalTicketsAttribute(): int
    {
        return $this->details()->sum('quantity');
    }

    /**
     * Get formatted total amount
     */
    public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'approved' => 'green',
            'cancelled' => 'red',
            default => 'gray',
        };
    }
}
