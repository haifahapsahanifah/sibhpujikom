<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PermintaanBaruNotification extends Notification
{
    use Queueable;

    protected $permintaan;

    /**
     * Create a new notification instance.
     */
    public function __construct($permintaan)
    {
        $this->permintaan = $permintaan;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'permintaan_id' => $this->permintaan->id,
            'nomor_surat' => $this->permintaan->nomor_surat,
            'pengaju' => $this->permintaan->user->nama ?? 'Unknown',
            'pesan' => 'mengajukan permintaan barang baru.',
            'url' => route('admin.permintaan.index')
        ];
    }

}
