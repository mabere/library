<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Log;

class StatusChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public object $requestModel,
        public string $type,
        public ?string $note = null
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        Log::info('Email notification queued/sent.', [
            'to' => $notifiable->email ?? null,
            'type' => $this->type,
            'request_id' => $this->requestModel->id ?? null,
            'status' => $this->requestModel->status ?? null,
        ]);

        $statusLabel = strtoupper(str_replace('_', ' ', $this->requestModel->status ?? '-'));
        $title = $this->type === 'skripsi'
            ? 'Status Pengajuan Skripsi'
            : 'Status Pengajuan Bebas Pustaka';

        $routeName = $this->type === 'skripsi'
            ? 'skripsi.show'
            : 'bebas_pustaka.show';

        $details = [
            'NIM: '.$this->requestModel->nim,
            'Nama: '.$this->requestModel->nama,
        ];

        if ($this->type === 'skripsi' && isset($this->requestModel->judul_skripsi)) {
            $details[] = 'Judul: '.$this->requestModel->judul_skripsi;
        }

        $details[] = 'Status: '.$statusLabel;

        $mail = (new MailMessage)
            ->subject($title)
            ->greeting('Halo '.$notifiable->name.',')
            ->line('Status pengajuan Anda telah diperbarui.')
            ->line(implode(' | ', $details));

        if ($this->note) {
            $mail->line('Catatan: '.$this->note);
        }

        $domain = strtolower((string) substr(strrchr((string) $notifiable->email, '@') ?: '', 1));
        if ($domain && $domain !== 'fkip-unilaki.ac.id') {
            $mail->cc('admin@fkip-unilaki.ac.id');
        }

        return $mail->markdown('emails.status-changed', [
            'title' => $title,
            'status' => $statusLabel,
            'details' => $details,
            'note' => $this->note,
            'url' => route($routeName, $this->requestModel->id),
            'name' => $notifiable->name,
        ]);
    }

    public function toDatabase(object $notifiable): array
    {
        $routeName = $this->type === 'skripsi'
            ? 'skripsi.show'
            : 'bebas_pustaka.show';

        return [
            'type' => $this->type,
            'request_id' => $this->requestModel->id,
            'status' => $this->requestModel->status,
            'note' => $this->note,
            'title' => $this->type === 'skripsi'
                ? 'Status Pengajuan Skripsi'
                : 'Status Pengajuan Bebas Pustaka',
            'url' => route($routeName, $this->requestModel->id),
        ];
    }
}
