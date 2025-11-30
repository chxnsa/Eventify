<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'slug',
        'description',
        'date_start',
        'date_end',
        'time_start',
        'time_end',
        'location',
        'venue',
        'address',
        'image',
        'status',
        'is_featured',
    ];

    protected $casts = [
        'date_start' => 'date',
        'date_end' => 'date',
        'time_start' => 'datetime:H:i',
        'time_end' => 'datetime:H:i',
        'is_featured' => 'boolean',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            if (empty($event->slug)) {
                $event->slug = Str::slug($event->name) . '-' . Str::random(6);
            }
        });
    }

    /**
     * Get the creator of this event
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Alias for user - organizer
     */
    public function organizer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get tickets for this event
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Get favorites for this event
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Get users who favorited this event
     */
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    
    /**
     * Relationship to reviews
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get bookings for this event through tickets
     */
    public function bookings()
    {
        return $this->hasManyThrough(
            BookingDetail::class,
            Ticket::class,
            'event_id',
            'ticket_id'
        );
    }

    /**
     * Scope for published events
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope for featured events
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for upcoming events
     */
    public function scopeUpcoming($query)
    {
        return $query->where('date_start', '>=', now()->toDateString());
    }

    /**
     * Check if event is available (has tickets with quota)
     */
    public function isAvailable(): bool
    {
        return $this->tickets()->whereRaw('quota > sold')->exists();
    }

    /**
     * Get minimum ticket price
     */
    public function getMinPriceAttribute()
    {
        return $this->tickets()->min('price') ?? 0;
    }

    /**
     * Get total tickets available
     */
    public function getTotalAvailableTicketsAttribute(): int
    {
        return $this->tickets()->sum(\DB::raw('quota - sold'));
    }


    /**
     * Get average rating
     */
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    /**
     * Get formatted date range
     */
    public function getFormattedDateAttribute(): string
    {
        if ($this->date_end && $this->date_start != $this->date_end) {
            return $this->date_start->format('D, d M') . ' - ' . $this->date_end->format('D, d M Y');
        }
        return $this->date_start->format('D, d M Y');
    }

    /**
     * Get formatted time
     */
    public function getFormattedTimeAttribute(): string
    {
        return date('H:i', strtotime($this->time_start));
    }
}
