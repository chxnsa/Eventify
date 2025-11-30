<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organizer = User::where('role', 'organizer')
                         ->where('organizer_status', 'approved')
                         ->first();
        
        $admin = User::where('role', 'admin')->first();

        $categories = Category::all()->keyBy('slug');

        $events = [
            [
                'user_id' => $organizer->id,
                'category_id' => $categories['concerts-music']->id,
                'name' => 'Summer Music Festival 2025',
                'description' => "Join us for the biggest music festival of the year! Experience amazing performances from top artists, enjoy great food, and create unforgettable memories.\n\nLine Up:\n1. The Headliners\n2. DJ Fusion\n3. Rock Legends\n4. Jazz Ensemble",
                'date_start' => '2025-12-20',
                'date_end' => '2025-12-21',
                'time_start' => '18:00',
                'time_end' => '23:00',
                'location' => 'Makassar',
                'venue' => 'Antang Stadium',
                'address' => 'Jl. Antang Raya No. 123, Makassar',
                'status' => 'published',
                'is_featured' => true,
                'tickets' => [
                    [
                        'name' => 'Regular Ticket',
                        'description' => 'Standard admission ticket',
                        'benefits' => "Access to main stage\nFood court access\nFree parking",
                        'price' => 300000,
                        'quota' => 500,
                        'sold' => 150,
                    ],
                    [
                        'name' => 'VIP Ticket',
                        'description' => 'Premium experience with exclusive perks',
                        'benefits' => "Front row access\nMeet & Greet\nExclusive merchandise\nVIP Lounge access\nFree drinks",
                        'price' => 750000,
                        'quota' => 100,
                        'sold' => 45,
                    ],
                    [
                        'name' => 'VVIP Ticket',
                        'description' => 'Ultimate experience',
                        'benefits' => "Backstage access\nDinner with artists\nAll VIP benefits\nComplimentary hotel",
                        'price' => 2000000,
                        'quota' => 20,
                        'sold' => 20, // Sold out
                    ],
                ],
            ],
            [
                'user_id' => $organizer->id,
                'category_id' => $categories['concerts-music']->id,
                'name' => 'Jazz Night Live',
                'description' => "An intimate evening of smooth jazz performances featuring local and international artists.\n\nLine Up:\n1. Smooth Jazz Quartet\n2. Vocal Jazz Ensemble",
                'date_start' => '2025-12-15',
                'date_end' => null,
                'time_start' => '19:30',
                'time_end' => '22:30',
                'location' => 'Makassar',
                'venue' => 'Grand Ballroom Hotel',
                'address' => 'Jl. Somba Opu No. 45, Makassar',
                'status' => 'published',
                'is_featured' => true,
                'tickets' => [
                    [
                        'name' => 'Regular',
                        'description' => 'Standard seating',
                        'benefits' => "Standard seating\nWelcome drink",
                        'price' => 250000,
                        'quota' => 200,
                        'sold' => 80,
                    ],
                    [
                        'name' => 'VIP Table',
                        'description' => 'Table for 4 with premium view',
                        'benefits' => "Premium table for 4\nFull course dinner\nUnlimited drinks\nPhoto opportunity",
                        'price' => 1500000,
                        'quota' => 25,
                        'sold' => 10,
                    ],
                ],
            ],
            [
                'user_id' => $organizer->id,
                'category_id' => $categories['workshops']->id,
                'name' => 'Photography Workshop for Beginners',
                'description' => "Master the basics of photography in this hands-on workshop. Bring your own camera!\n\nYou will learn:\n1. Camera basics\n2. Composition techniques\n3. Lighting fundamentals\n4. Post-processing intro",
                'date_start' => '2025-12-28',
                'date_end' => null,
                'time_start' => '10:00',
                'time_end' => '16:00',
                'location' => 'Bandung',
                'venue' => 'Creative Space Bandung',
                'address' => 'Jl. Dago No. 50, Bandung',
                'status' => 'published',
                'is_featured' => false,
                'tickets' => [
                    [
                        'name' => 'Workshop Pass',
                        'description' => 'Full workshop access',
                        'benefits' => "6 hours hands-on learning\nPractice session\nLunch & snacks\nCertificate",
                        'price' => 350000,
                        'quota' => 30,
                        'sold' => 12,
                    ],
                ],
            ],
            [
                'user_id' => $organizer->id,
                'category_id' => $categories['sports-fitness']->id,
                'name' => 'Makassar Marathon 2026',
                'description' => "Join thousands of runners in the annual Makassar Marathon! Choose your distance and challenge yourself.\n\nCategories:\n1. Full Marathon (42K)\n2. Half Marathon (21K)\n3. Fun Run (10K)\n4. Family Run (5K)",
                'date_start' => '2026-02-15',
                'date_end' => null,
                'time_start' => '05:00',
                'time_end' => '12:00',
                'location' => 'Makassar',
                'venue' => 'Losari Beach',
                'address' => 'Pantai Losari, Makassar',
                'status' => 'published',
                'is_featured' => true,
                'tickets' => [
                    [
                        'name' => 'Full Marathon (42K)',
                        'description' => 'Full marathon distance',
                        'benefits' => "Race bib & timing chip\nFinisher medal\nRunning shirt\nHydration stations\nMedical support",
                        'price' => 500000,
                        'quota' => 1000,
                        'sold' => 450,
                    ],
                    [
                        'name' => 'Half Marathon (21K)',
                        'description' => 'Half marathon distance',
                        'benefits' => "Race bib & timing chip\nFinisher medal\nRunning shirt\nHydration stations\nMedical support",
                        'price' => 400000,
                        'quota' => 2000,
                        'sold' => 890,
                    ],
                    [
                        'name' => 'Fun Run (10K)',
                        'description' => '10K fun run',
                        'benefits' => "Race bib\nFinisher medal\nRunning shirt\nHydration stations",
                        'price' => 250000,
                        'quota' => 3000,
                        'sold' => 1500,
                    ],
                    [
                        'name' => 'Family Run (5K)',
                        'description' => '5K family run',
                        'benefits' => "Race bib\nFinisher medal\nRunning shirt",
                        'price' => 150000,
                        'quota' => 2000,
                        'sold' => 800,
                    ],
                ],
            ],
            [
                'user_id' => $organizer->id,
                'category_id' => $categories['art-exhibition']->id,
                'name' => 'Contemporary Art Exhibition',
                'description' => "Explore stunning contemporary artworks from emerging Indonesian artists.\n\nFeatured Artists:\n1. Local rising stars\n2. International guest artists\n3. Interactive installations",
                'date_start' => '2026-01-15',
                'date_end' => '2026-02-15',
                'time_start' => '10:00',
                'time_end' => '20:00',
                'location' => 'Makassar',
                'venue' => 'Fort Rotterdam Gallery',
                'address' => 'Jl. Ujung Pandang, Makassar',
                'status' => 'published',
                'is_featured' => false,
                'tickets' => [
                    [
                        'name' => 'Single Entry',
                        'description' => 'One-time entry',
                        'benefits' => "One-time gallery access\nExhibition catalog",
                        'price' => 75000,
                        'quota' => 1000,
                        'sold' => 234,
                    ],
                    [
                        'name' => 'Season Pass',
                        'description' => 'Unlimited entry for the month',
                        'benefits' => "Unlimited entry during exhibition\nExhibition catalog\nArtist meet & greet\n10% shop discount",
                        'price' => 200000,
                        'quota' => 200,
                        'sold' => 67,
                    ],
                ],
            ],
            [
                'user_id' => $organizer->id,
                'category_id' => $categories['family-kids']->id,
                'name' => 'Kids Science Fair 2026',
                'description' => "A fun and educational event for children! Interactive science experiments, workshops, and shows.\n\nActivities:\n1. Science experiments\n2. Robot workshop\n3. Planetarium show\n4. DIY crafts",
                'date_start' => '2026-01-20',
                'date_end' => '2026-01-21',
                'time_start' => '09:00',
                'time_end' => '17:00',
                'location' => 'Surabaya',
                'venue' => 'Surabaya Convention Hall',
                'address' => 'Jl. Basuki Rahmat No. 1, Surabaya',
                'status' => 'published',
                'is_featured' => false,
                'tickets' => [
                    [
                        'name' => 'Child Ticket',
                        'description' => 'For children age 4-12',
                        'benefits' => "Full access to all activities\nScience kit\nSnack box\nCertificate",
                        'price' => 150000,
                        'quota' => 500,
                        'sold' => 189,
                    ],
                    [
                        'name' => 'Family Package (2A+2C)',
                        'description' => '2 Adults + 2 Children',
                        'benefits' => "Full access for 4 people\n2 Science kits\n4 Snack boxes\n2 Certificates\nFamily photo",
                        'price' => 450000,
                        'quota' => 150,
                        'sold' => 78,
                    ],
                ],
            ],
            [
                'user_id' => $organizer->id,
                'category_id' => $categories['festival']->id,
                'name' => 'Food & Culture Festival',
                'description' => "Celebrate Indonesian culinary heritage! Taste dishes from across the archipelago and enjoy cultural performances.\n\nHighlights:\n1. 50+ food vendors\n2. Traditional dance shows\n3. Cooking demonstrations\n4. Live music",
                'date_start' => '2026-03-15',
                'date_end' => '2026-03-17',
                'time_start' => '10:00',
                'time_end' => '22:00',
                'location' => 'Makassar',
                'venue' => 'CPI Convention Center',
                'address' => 'Jl. Metro Tanjung Bunga, Makassar',
                'status' => 'published',
                'is_featured' => true,
                'tickets' => [
                    [
                        'name' => 'Daily Pass',
                        'description' => 'Single day entry',
                        'benefits' => "One day access\nFood voucher Rp 50.000",
                        'price' => 50000,
                        'quota' => 2000,
                        'sold' => 567,
                    ],
                    [
                        'name' => '3-Day Pass',
                        'description' => 'Full festival access',
                        'benefits' => "3 days access\nFood voucher Rp 150.000\nMerchandise\nPriority seating for shows",
                        'price' => 120000,
                        'quota' => 500,
                        'sold' => 234,
                    ],
                ],
            ],
        ];

        foreach ($events as $eventData) {
            $tickets = $eventData['tickets'];
            unset($eventData['tickets']);

            $event = Event::create($eventData);

            foreach ($tickets as $ticketData) {
                $event->tickets()->create($ticketData);
            }
        }
    }
}