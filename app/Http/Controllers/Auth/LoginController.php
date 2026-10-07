<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($data, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = auth()->user();

            // Rol bo'yicha yo'naltirish
            if ($user->role_id === 1) {
                // Admin
                return redirect()->intended(route('admin.dashboard'));
            }

            if ($user->role_id === 2) {
                // O'qituvchi
                return redirect()->intended(route('teacher.dashboard'));
            }

            if ($user->role_id === 3) {
                // Talaba (starosta bo'lsa — jurnalga)
                if ($user->isStarosta()) {
                    return redirect()->intended(route('starosta.journal'));
                }

                // Oddiy talaba — bosh sahifaga
                return redirect()->intended(route('timetable'));
            }

            // Default — bosh sahifa
            return redirect()->intended(route('timetable'));
        }

        return back()->withErrors(['email' => 'Email yoki parol noto‘g‘ri.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('timetable');
    }
}