<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with(['category', 'tickets'])
            ->published()
            ->upcoming();

        // Filter by search keyword
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%')
                ->orWhere('venue', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by location
        if ($request->filled('location')) {
            $query->where('location', $request->location);
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('date_start', $request->date);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

       // Sorting
        $sort = $request->get('sort', 'date');
        switch ($sort) {
            case 'name':
                $query->orderBy('name');
                break;
            case 'price_low':
                // Sort by minimum ticket price (low to high)
                $query->withMin('tickets', 'price')
                    ->orderBy('tickets_min_price', 'asc');
                break;
            case 'price_high':
                // Sort by maximum ticket price (high to low)
                $query->withMax('tickets', 'price')
                    ->orderBy('tickets_max_price', 'desc');
                break;
            default:
                $query->orderBy('date_start');
        }

    $events = $query->paginate(12);
    $categories = Category::all();

    return view('events.index', compact('events', 'categories'));
}

    public function show($slug)
    {
        $event = Event::with(['category', 'tickets', 'organizer', 'reviews.user'])
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();

        // Check if user has favorited
        $isFavorited = false;
        if (auth()->check()) {
            $isFavorited = auth()->user()->hasFavorited($event);
        }

        // Related events
        $relatedEvents = Event::with(['category', 'tickets'])
            ->published()
            ->upcoming()
            ->where('category_id', $event->category_id)
            ->where('id', '!=', $event->id)
            ->take(4)
            ->get();

        return view('events.show', compact('event', 'isFavorited', 'relatedEvents'));
    }

    public function byCategory($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $events = Event::with(['category', 'tickets'])
            ->published()
            ->upcoming()
            ->where('category_id', $category->id)
            ->orderBy('date_start', 'asc')
            ->paginate(12);

        $categories = Category::all();
        $activeCategory = $slug;

        return view('events.index', compact('events', 'categories', 'activeCategory', 'category'));
    }

    public function search(Request $request)
    {
        return $this->index($request);
    }
}