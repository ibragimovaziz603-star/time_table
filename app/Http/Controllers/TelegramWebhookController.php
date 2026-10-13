<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $update = $request->all();
        Log::info('=== WEBHOOK ISHGA TUSHDI ===');
        Log::info('Update:', $update);

        // Xabarni olish (message, channel_post, edited_message)
        $message = $update['message']
                ?? $update['channel_post']
                ?? $update['edited_message']
                ?? $update['edited_channel_post']
                ?? null;

        if (!$message || !isset($message['text'])) {
            return response()->json(['ok' => true]);
        }

        $chatId    = $message['chat']['id'];
        $messageId = $message['message_id'];
        $text      = trim($message['text']);

        // /start komandasi
        if ($text === '/start') {
            $this->send($chatId, "👋 Salom! Role o'zgartirish uchun:\n\n"
                . "email: q0@gmail.com\n"
                . "role_id=3");
            return response()->json(['ok' => true]);
        }

        // Parse
        $data = $this->parse($text);

        if (!$data['email'] || !$data['role_id']) {
            $this->send($chatId, "❌ Xato format:\n\nemail: q0@gmail.com\nrole_id=3");
            return response()->json(['ok' => true]);
        }

        // User topish
        $user = User::where('email', $data['email'])->first();
        if (!$user) {
            $this->send($chatId, "❌ Bu email topilmadi: `{$data['email']}`");
            return response()->json(['ok' => true]);
        }

        // Rol tekshirish
        $role = Role::find($data['role_id']);
        if (!$role) {
            $this->send($chatId, "❌ role_id={$data['role_id']} mavjud emas.\n\n"
                . "1=Admin, 2=O'qituvchi, 3=Talaba, 4=Kutayotgan");
            return response()->json(['ok' => true]);
        }

        $oldRole = $user->role?->name ?? '—';

        // Rol yangilash
        $user->update(['role_id' => $data['role_id']]);

        // ============ ESKI XABARNI O'CHIRISH ============
        $this->deleteMessage($chatId, $messageId);

        // ============ YANGI XABAR YUBORISH ============
        $newMessage = "ism: {$user->first_name}\n"
                    . "familya: {$user->last_name}\n"
                    . "otasi-onasi ismi: " . ($user->middle_name ?? '—') . "\n"
                    . "email: {$user->email}\n"
                    . "role_id={$data['role_id']}";

        $this->send($chatId, $newMessage);

        Log::info("Role yangilandi: {$user->email} → {$role->label}");

        return response()->json(['ok' => true]);
    }

    /** Matndan ma'lumotlarni ajratish */
    private function parse(string $text): array
    {
        $result = ['email' => null, 'role_id' => null];
        $lines = preg_split('/\r\n|\r|\n/', $text);

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;

            if (preg_match('/^email:\s*(.+)$/ui', $line, $m)) {
                $result['email'] = trim($m[1]);
            }
            if (preg_match('/role_id\s*[=:]\s*(\d+)/ui', $line, $m)) {
                $result['role_id'] = (int) $m[1];
            }
        }

        return $result;
    }

    /** Telegramga xabar yuborish */
    private function send($chatId, string $text): void
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        if (!$token) {
            Log::error('TELEGRAM_BOT_TOKEN yo\'q');
            return;
        }

        try {
            $response = Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
                'chat_id'    => $chatId,
                'text'       => $text,
            ]);

            Log::info('Xabar yuborildi:', [
                'status' => $response->status(),
                'body'   => $response->json(),
            ]);
        } catch (\Exception $e) {
            Log::error('Xabar yuborishda xato: ' . $e->getMessage());
        }
    }

    /** Xabarni o'chirish */
    private function deleteMessage($chatId, $messageId): void
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        if (!$token) return;

        try {
            $response = Http::post("https://api.telegram.org/bot{$token}/deleteMessage", [
                'chat_id'    => $chatId,
                'message_id' => $messageId,
            ]);

            Log::info('Xabar o\'chirildi:', [
                'status' => $response->status(),
                'body'   => $response->json(),
            ]);
        } catch (\Exception $e) {
            Log::error('Xabar o\'chirishda xato: ' . $e->getMessage());
        }
    }
}