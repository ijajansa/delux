<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class HotelController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $canManageHotels = $user->isSuperAdmin();

        $hotelsQuery = Hotel::with('partner')->latest();

        if (!$canManageHotels) {
            $hotelsQuery->where('partner_id', $user->id);
        }

        $hotels = $hotelsQuery->paginate(15);
        $partners = $canManageHotels
            ? User::employees()->orderBy('name')->get()
            : collect();

        return view('hotels.index', compact('hotels', 'partners', 'canManageHotels'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'name' => 'required|string|max:255',
        ];

        if ($user->isSuperAdmin()) {
            $rules['partner_id'] = [
                'required',
                Rule::exists('users', 'id')->where('role', User::ROLE_EMPLOYEE),
            ];
        }

        $request->validate($rules);

        $partnerId = $user->isSuperAdmin() ? $request->partner_id : $user->id;

        $hotel = Hotel::create([
            'name' => $request->name,
            'partner_id' => $partnerId,
            'is_active' => true,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Hotel added successfully!',
                'hotel' => [
                    'id' => $hotel->id,
                    'name' => $hotel->name,
                    'is_active' => $hotel->is_active,
                    'partner_id' => $hotel->partner_id,
                ],
            ], 201);
        }

        return back()->with('success', 'Hotel added successfully!');
    }

    public function toggle(Hotel $hotel)
    {
        abort_unless(Auth::user()->isSuperAdmin(), 403);

        $hotel->update(['is_active' => !$hotel->is_active]);

        if (request()->expectsJson()) {
            return response()->json([
                'message' => 'Hotel status updated!',
                'hotel' => [
                    'id' => $hotel->id,
                    'is_active' => $hotel->is_active,
                ],
            ]);
        }

        return back()->with('success', 'Hotel status updated!');
    }
}
