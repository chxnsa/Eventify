<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'ticket_id',
        'quantity',
        'price',
        'subtotal',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    /**
     * Get the booking
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the ticket
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Get the event through ticket
     */
    public function event()
    {
        return $this->hasOneThrough(
            Event::class,
            Ticket::class,
            'id',
            'id',
            'ticket_id',
            'event_id'
        );
    }

    /**
     * Calculate subtotal before saving
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($detail) {
            if (empty($detail->subtotal)) {
                $detail->subtotal = $detail->price * $detail->quantity;
            }
        });
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Get formatted subtotal
     */
    public function getFormattedSubtotalAttribute(): string
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }
}
