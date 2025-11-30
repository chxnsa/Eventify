<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter organizer status
        if ($request->filled('organizer_status')) {
            $query->where('organizer_status', $request->organizer_status);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->latest()->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['events', 'bookings.details.ticket.event', 'reviews']);

        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['required', 'in:admin,organizer,user'],
        ]);

        $data = $request->only(['name', 'email', 'phone', 'role']);

        // If changing to organizer, set status
        if ($request->role === 'organizer' && $user->role !== 'organizer') {
            $data['organizer_status'] = 'approved';
        }

        // If changing from organizer, clear status
        if ($request->role !== 'organizer') {
            $data['organizer_status'] = null;
        }

        $user->update($data);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        // Prevent deleting self
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        // Delete avatar
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    // Organizer Approval
    public function pendingOrganizers()
    {
        $organizers = User::where('role', 'organizer')
            ->where('organizer_status', 'pending')
            ->latest()
            ->paginate(15);

        return view('admin.users.pending-organizers', compact('organizers'));
    }

    public function approveOrganizer(User $user)
    {
        if ($user->role !== 'organizer' || $user->organizer_status !== 'pending') {
            return back()->with('error', 'Invalid organizer.');
        }

        $user->update(['organizer_status' => 'approved']);

        return back()->with('success', 'Organizer approved successfully.');
    }

    public function rejectOrganizer(Request $request, User $user)
    {
        if ($user->role !== 'organizer' || $user->organizer_status !== 'pending') {
            return back()->with('error', 'Invalid organizer.');
        }

        $request->validate([
            'rejection_reason' => ['required', 'string', 'max:500'],
        ]);

        $user->update([
            'organizer_status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', 'Organizer rejected.');
    }
}