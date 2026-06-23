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
        Hotel::create($request->all());
        return back()->with('success', 'Hotel added successfully!');
    }

    public function toggle(Hotel $hotel)
    {
        $hotel->update(['is_active' => !$hotel->is_active]);
        return back()->with('success', 'Hotel status updated!');
    }
}
