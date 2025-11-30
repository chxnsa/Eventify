<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Featured Events
        $featuredEvents = Event::with(['category', 'tickets'])
            ->published()
            ->featured()
            ->upcoming()
            ->take(6)
            ->get();

        // Upcoming Events
        $upcomingEvents = Event::with(['category', 'tickets'])
            ->published()
            ->upcoming()
            ->orderBy('date_start', 'asc')
            ->take(8)
            ->get();

        // Categories
        $categories = Category::all();

        return view('home.index', compact('featuredEvents', 'upcomingEvents', 'categories'));
    }
}