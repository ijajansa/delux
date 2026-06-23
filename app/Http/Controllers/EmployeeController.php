<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = User::employees();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('contact_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $employees = $query->latest()->paginate(15);

        return view('admin.employees.index', compact('employees'));
    }

    public function create()
    {
        return view('admin.employees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_number' => 'required|string|digits:10|unique:users,contact_number',
            'login_password' => 'required|string|digits:4|unique:users,login_password',
            'login_password_confirmation' => 'required|string|digits:4|same:login_password',
        ], [
            'login_password.digits' => 'PIN must be exactly 4 digits.',
            'login_password.unique' => 'An employee with this password already exists.',
            'login_password_confirmation.same' => 'PIN confirmation does not match.',
            'contact_number.unique' => 'An employee with this contact number already exists.',
        ]);

        User::create([
            'name' => $request->name,
            'contact_number' => $request->contact_number,
            'password' => $request->login_password,
            'login_password' => $request->login_password,
            'role' => User::ROLE_EMPLOYEE,
            'is_active' => true,
        ]);

        return redirect()->route('employees.index')
            ->with('success', 'Employee registered successfully!');
    }

    public function edit(string $id)
    {
        $employee = User::employees()->findOrFail($id);
        return view('admin.employees.edit', compact('employee'));
    }

    public function update(Request $request, string $id)
    {
        $employee = User::employees()->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'contact_number' => [
                'required', 'string', 'digits:10',
                Rule::unique('users', 'contact_number')->ignore($employee->id),
            ],
            'login_password' => [
                'nullable',
                'string',
                'digits:4',
                Rule::unique('users', 'login_password')->ignore($employee->id),
            ],
            'login_password_confirmation' => [
                'nullable',
                'string',
                'digits:4',
            ],
        ], [
            'login_password.digits' => 'PIN must be exactly 4 digits.',
            'login_password.unique' => 'This password is already used by another employee.',
        ]);

        if ($request->filled('login_password') && $request->login_password !== $request->login_password_confirmation) {
            return back()
                ->withErrors(['login_password_confirmation' => 'PIN confirmation does not match.'])
                ->withInput();
        }

        $employee->name = $request->name;
        $employee->contact_number = $request->contact_number;

        if ($request->filled('login_password')) {
            $employee->password = $request->login_password;
            $employee->login_password = $request->login_password;
        }

        $employee->save();

        return redirect()->route('employees.index')
            ->with('success', 'Employee updated successfully!');
    }

    public function toggleStatus(string $id)
    {
        $employee = User::employees()->findOrFail($id);
        $employee->is_active = !$employee->is_active;
        $employee->save();

        $status = $employee->is_active ? 'activated' : 'deactivated';
        return redirect()->route('employees.index')
            ->with('success', "Employee {$status} successfully!");
    }
}
