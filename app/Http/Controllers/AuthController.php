<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }
        return view('auth.login');
    }

    public function superadminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if (!$user->isSuperAdmin()) {
                Auth::logout();
                return back()->withErrors(['email' => 'This account is not a SuperAdmin.'])->withInput();
            }

            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'This account has been deactivated.'])->withInput();
            }

            $request->session()->regenerate();
            return redirect()->intended('/admin/dashboard');
        }

        return back()->withErrors(['email' => 'Invalid email or password.'])->withInput();
    }

    public function employeeLogin(Request $request)
    {
        $request->validate([
            'password' => 'required|string|digits:4',
        ]);

        // For now, if only one partner exists, we just check the PIN
        $user = User::where('role', User::ROLE_EMPLOYEE)
                     ->where('is_active', true)
                     ->first();

        if (!$user) {
            return back()->withErrors(['password' => 'No active partner account found.'])->withInput();
        }

        if (!Auth::attempt(['contact_number' => $user->contact_number, 'password' => $request->password])) {
            return back()->withErrors(['password' => 'Invalid PIN.'])->withInput();
        }

        $request->session()->regenerate();
        return redirect()->intended('/employee/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    protected function redirectByRole($user)
    {
        if ($user->isSuperAdmin()) {
            return redirect('/admin/dashboard');
        }
        return redirect('/employee/dashboard');
    }
}
