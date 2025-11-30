<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'description',
        'benefits',
        'price',
        'quota',
        'sold',
        'image',
        'sale_start',
        'sale_end',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_start' => 'datetime',
        'sale_end' => 'datetime',
    ];

    /**
     * Get the event this ticket belongs to
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get booking details for this ticket
     */
    public function bookingDetails()
    {
        return $this->hasMany(BookingDetail::class);
    }

    /**
     * Check if ticket is available
     */
    public function isAvailable(): bool
    {
        return $this->getAvailableQuantityAttribute() > 0;
    }

    /**
     * Get available quantity
     */
    public function getAvailableQuantityAttribute(): int
    {
        return max(0, $this->quota - $this->sold);
    }

    /**
     * Check if ticket is sold out
     */
    public function isSoldOut(): bool
    {
        return $this->sold >= $this->quota;
    }

    /**
     * Check if ticket sale is active
     */
    public function isSaleActive(): bool
    {
        $now = now();
        
        if ($this->sale_start && $now < $this->sale_start) {
            return false;
        }
        
        if ($this->sale_end && $now > $this->sale_end) {
            return false;
        }
        
        return true;
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Get benefits as array
     */
    public function getBenefitsArrayAttribute(): array
    {
        if (empty($this->benefits)) {
            return [];
        }
        
        // If stored as JSON
        $decoded = json_decode($this->benefits, true);
        if (is_array($decoded)) {
            return $decoded;
        }
        
        // If stored as newline-separated text
        return array_filter(array_map('trim', explode("\n", $this->benefits)));
    }
}
