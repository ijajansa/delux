<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function index()
    {
        $hotels = Hotel::latest()->paginate(15);
        return view('hotels.index', compact('hotels'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $hotel = Hotel::create($request->only('name'));

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Hotel added successfully!',
                'hotel' => [
                    'id' => $hotel->id,
                    'name' => $hotel->name,
                    'is_active' => $hotel->is_active,
                ],
            ], 201);
        }

        return back()->with('success', 'Hotel added successfully!');
    }

    public function toggle(Hotel $hotel)
    {
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
