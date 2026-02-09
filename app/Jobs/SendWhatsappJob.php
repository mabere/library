<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendWhatsappJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $type,
        public object $requestModel,
        public string $note
    ) {
    }

    public function handle(): void
    {
        $phone = $this->user->phone_number ?? $this->user->phone ?? null;

        $token = config('services.fonnte.token');
        $endpoint = config('services.fonnte.endpoint');

        if (!$phone || !$token || !$endpoint) {
            Log::info('WA not sent (missing config/phone).', [
                'user_id' => $this->user->id,
            ]);
            return;
        }

        $status = strtoupper(str_replace('_', ' ', $this->requestModel->status ?? '-'));
        $label = $this->type === 'skripsi' ? 'Skripsi' : 'Bebas Pustaka';
        $message = "Status $label:\n".
            "NIM: {$this->requestModel->nim}\n".
            "Nama: {$this->requestModel->nama}\n".
            "Status: {$status}\n".
            "Catatan: {$this->note}";

        $response = Http::withHeaders([
            'Authorization' => $token,
        ])->asForm()->post($endpoint, [
            'target' => $phone,
            'message' => $message,
        ]);

        Log::info('WA sent (fonnte).', [
            'user_id' => $this->user->id,
            'phone' => $phone,
            'status' => $response->status(),
            'body' => $response->json() ?? $response->body(),
        ]);
    }
}
