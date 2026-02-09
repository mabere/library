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

class SendWhatsappTestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $message
    ) {
    }

    public function handle(): void
    {
        $phone = $this->user->phone_number ?? null;
        $token = config('services.fonnte.token');
        $endpoint = config('services.fonnte.endpoint');

        if (!$phone || !$token || !$endpoint) {
            Log::info('WA test not sent (missing config/phone).', [
                'user_id' => $this->user->id,
            ]);
            return;
        }

        $response = Http::withHeaders([
            'Authorization' => $token,
        ])->asForm()->post($endpoint, [
            'target' => $phone,
            'message' => $this->message,
        ]);

        Log::info('WA test sent (fonnte).', [
            'user_id' => $this->user->id,
            'phone' => $phone,
            'status' => $response->status(),
            'body' => $response->json() ?? $response->body(),
        ]);
    }
}
