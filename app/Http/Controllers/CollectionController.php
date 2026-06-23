<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\ClothType;
use App\Models\Collection;
use App\Models\CollectionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CollectionController extends Controller
{
    public function create()
    {
        $hotels = Hotel::where('is_active', true)
            ->where('partner_id', Auth::id())
            ->get();
        
        // For each hotel, check if a collection exists today
        $today = now()->startOfDay();
        foreach ($hotels as $hotel) {
            $hotel->collected_today = Collection::where('hotel_id', $hotel->id)
                ->where('collected_at', '>=', $today)
                ->exists();
            
            if ($hotel->collected_today) {
                $hotel->today_collection = Collection::where('hotel_id', $hotel->id)
                    ->where('collected_at', '>=', $today)
                    ->first();
            }
        }

        return view('employee.collections.create', compact('hotels'));
    }

    public function entry(Hotel $hotel)
    {
        abort_unless($hotel->partner_id === Auth::id(), 403);

        $today = now()->startOfDay();
        $collection = Collection::where('hotel_id', $hotel->id)
            ->where('collected_at', '>=', $today)
            ->first();

        $clothTypes = ClothType::where('is_active', true)
            ->where('partner_id', Auth::id())
            ->get();
        
        // If editing an existing collection, map quantities
        $existingQuantities = [];
        if ($collection) {
            // Check if collection is within 3-day edit window
            if ($collection->collected_at->lt(now()->subDays(3)->startOfDay())) {
                return redirect()->route('collections.history')->with('error', 'Edit window (3 days) has expired for this record.');
            }
            $existingQuantities = $collection->items->pluck('quantity', 'cloth_type_id')->toArray();
        }

        return view('employee.collections.entry', compact('hotel', 'clothTypes', 'collection', 'existingQuantities'));
    }

    public function store(Request $request, Hotel $hotel)
    {
        abort_unless($hotel->partner_id === Auth::id(), 403);

        $request->validate([
            'quantities' => 'required|array',
            'quantities.*' => 'nullable|integer|min:0',
        ]);

        $today = now()->startOfDay();
        $collection = Collection::updateOrCreate(
            [
                'hotel_id' => $hotel->id,
                'collected_at' => Collection::where('hotel_id', $hotel->id)
                    ->where('collected_at', '>=', $today)
                    ->exists() 
                    ? Collection::where('hotel_id', $hotel->id)->where('collected_at', '>=', $today)->first()->collected_at
                    : now()
            ],
            [
                'user_id' => Auth::id(),
            ]
        );

        // Ensure we handle the update case for items
        $collection->items()->delete();

        foreach ($request->quantities as $clothTypeId => $quantity) {
            if ($quantity > 0) {
                CollectionItem::create([
                    'collection_id' => $collection->id,
                    'cloth_type_id' => $clothTypeId,
                    'quantity' => $quantity,
                ]);
            }
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Collection saved successfully!',
                'collection_id' => $collection->id,
                'redirect' => route('employee.dashboard'),
            ]);
        }

        return redirect()->route('employee.dashboard')->with('success', 'Collection saved successfully!');
    }

    public function history()
    {
        $collections = Collection::with(['hotel', 'items.clothType'])
            ->where('user_id', Auth::id())
            ->latest('collected_at')
            ->paginate(15);
        return view('employee.collections.history', compact('collections'));
    }
}
