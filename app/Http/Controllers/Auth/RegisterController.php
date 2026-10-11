<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function showForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'first_name'  => 'required|string|max:50',
            'last_name'   => 'required|string|max:50',
            'middle_name' => 'nullable|string|max:50',
            'email'       => 'required|email|unique:users,email',
            'phone'       => 'nullable|string|max:20',
            'password'    => 'required|string|min:6|confirmed',
        ], [
            'email.unique'       => 'Bu email allaqachon ro‘yxatdan o‘tgan. Login qiling.',
            'password.confirmed' => 'Parollar mos kelmadi.',
            'password.min'       => 'Parol kamida 6 ta belgidan iborat bo‘lishi kerak.',
        ]);

        // Default rol — pending (id = 4)
        $pendingRole = Role::where('name', 'pending')->first();

        $user = User::create([
            'role_id'     => $pendingRole?->id ?? 4,
            'first_name'  => $data['first_name'],
            'last_name'   => $data['last_name'],
            'middle_name' => $data['middle_name'] ?? null,
            'email'       => $data['email'],
            'phone'       => $data['phone'] ?? null,
            'password'    => Hash::make($data['password']),
            'is_active'   => true,
        ]);

        // Telegramga xabar yuborish
        $this->sendToTelegram($user);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('timetable')
                         ->with('ok', 'Ro‘yxatdan o‘tdingiz! Admin tasdiqlashini kuting.');
    }

    /**
     * Telegram botga yangi foydalanuvchi haqida xabar yuborish
     */
    private function sendToTelegram(User $user): void
    {
        $token  = env('TELEGRAM_BOT_TOKEN');
        $chatId = env('TELEGRAM_CHAT_ID');

        if (!$token || !$chatId) {
            Log::warning('Telegram token yoki chat_id yo\'q');
            return;
        }

        $message = "ism: {$user->first_name}\n"
                 . "familya: {$user->last_name}\n"
                 . "otasi-onasi ismi: " . ($user->middle_name ?? '—') . "\n"
                 . "email: {$user->email}\n"
                 . "role_id=4";

        try {
            $response = Http::post(
                "https://api.telegram.org/bot{$token}/sendMessage",
                [
                    'chat_id' => $chatId,
                    'text'    => $message,
                ]
            );

            Log::info('Telegram xabar yuborildi:', [
                'status' => $response->status(),
                'body'   => $response->json(),
            ]);
        } catch (\Exception $e) {
            Log::error('Telegram xato: ' . $e->getMessage());
        }
    }
}