<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = auth()->user()
            ->favorites()
            ->with(['event.category', 'event.tickets'])
            ->latest()
            ->paginate(12);

        return view('user.favorites.index', compact('favorites'));
    }

    public function toggle($eventId)
    {
        $event = Event::findOrFail($eventId);
        $user = auth()->user();
        
        $favorite = Favorite::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json([
                'favorited' => false,
                'message' => 'Event removed from favorites'
            ]);
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'event_id' => $event->id
            ]);
            return response()->json([
                'favorited' => true,
                'message' => 'Event added to favorites'
            ]);
        }
    }

    public function destroy(Favorite $favorite)
    {
        if ($favorite->user_id !== auth()->id()) {
            abort(403);
        }

        $favorite->delete();

        return back()->with('success', 'Event removed from favorites.');
    }
}