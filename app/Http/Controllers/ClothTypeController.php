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
        $request->validate(['name' => 'required|string|max:255']);
        ClothType::create($request->all());
        return back()->with('success', 'Cloth type added successfully!');
    }

    public function toggle(ClothType $clothType)
    {
        $clothType->update(['is_active' => !$clothType->is_active]);
        return back()->with('success', 'Cloth type status updated!');
    }
}
