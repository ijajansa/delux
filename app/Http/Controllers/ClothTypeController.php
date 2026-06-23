<?php

namespace App\Http\Controllers;

use App\Models\ClothType;
use Illuminate\Http\Request;

class ClothTypeController extends Controller
{
    public function index()
    {
        $clothTypes = ClothType::latest()->paginate(15);
        return view('cloth-types.index', compact('clothTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:cloth_types,name',
        ]);

        $clothType = ClothType::create([
            'name' => $validated['name'],
            'is_active' => true,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Cloth type added successfully!',
                'clothType' => [
                    'id' => $clothType->id,
                    'name' => $clothType->name,
                ],
            ], 201);
        }

        return back()->with('success', 'Cloth type added successfully!');
    }

    public function toggle(ClothType $clothType)
    {
        $clothType->update(['is_active' => !$clothType->is_active]);
        return back()->with('success', 'Cloth type status updated!');
    }
}
