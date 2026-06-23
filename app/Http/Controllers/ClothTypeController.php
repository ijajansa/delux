<?php

namespace App\Http\Controllers;

use App\Models\ClothType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ClothTypeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $canManageClothTypes = $user->isSuperAdmin();

        $clothTypesQuery = ClothType::with('partner')->latest();

        if (!$canManageClothTypes) {
            $clothTypesQuery->where('partner_id', $user->id);
        }

        $clothTypes = $clothTypesQuery->paginate(15);
        $partners = $canManageClothTypes
            ? User::employees()->orderBy('name')->get()
            : collect();

        return view('cloth-types.index', compact('clothTypes', 'partners', 'canManageClothTypes'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $partnerId = $user->isSuperAdmin() ? $request->partner_id : $user->id;

        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('cloth_types', 'name')->where(function ($query) use ($partnerId) {
                    return $query->where('partner_id', $partnerId);
                }),
            ],
        ];

        if ($user->isSuperAdmin()) {
            $rules['partner_id'] = [
                'required',
                Rule::exists('users', 'id')->where('role', User::ROLE_EMPLOYEE),
            ];
        }

        $validated = $request->validate($rules);
        $partnerId = $user->isSuperAdmin() ? $validated['partner_id'] : $user->id;

        $clothType = ClothType::create([
            'name' => $validated['name'],
            'is_active' => true,
            'partner_id' => $partnerId,
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
        if (!Auth::user()->isSuperAdmin()) {
            abort_unless($clothType->partner_id === Auth::id(), 403);
        }

        $clothType->update(['is_active' => !$clothType->is_active]);

        if (request()->expectsJson()) {
            return response()->json([
                'message' => 'Cloth type status updated!',
                'clothType' => [
                    'id' => $clothType->id,
                    'is_active' => $clothType->is_active,
                ],
            ]);
        }

        return back()->with('success', 'Cloth type status updated!');
    }
}
