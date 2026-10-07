<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name'  => 'required|string|max:50',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|string|min:6|confirmed',
        ], [
            'email.unique'       => 'Bu email allaqachon ro‘yxatdan o‘tgan. Login qiling.',
            'password.confirmed' => 'Parollar mos kelmadi.',
            'password.min'       => 'Parol kamida 6 ta belgidan iborat bo‘lishi kerak.',
        ]);

        // Default rol — student (id = 3)
        $studentRole = Role::where('name', 'student')->first();

        $user = User::create([
            'role_id'    => $studentRole?->id ?? 3,
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'password'   => Hash::make($data['password']),  // bcrypt hash
            'is_active'  => true,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('timetable')
                         ->with('ok', 'Muvaffaqiyatli ro‘yxatdan o‘tdingiz!');
    }
}