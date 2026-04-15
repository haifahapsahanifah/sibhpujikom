<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StatusPermintaanNotification extends Notification
{
    use Queueable;

    protected $permintaan;
    protected $status;

    /**
     * Create a new notification instance.
     */
    public function __construct($permintaan, $status)
    {
        $this->permintaan = $permintaan;
        $this->status = $status; // 'disetujui' atau 'ditolak'
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
        $kata_status = $this->status == 'disetujui' ? 'telah disetujui' : 'telah ditolak';
        
        return [
            'permintaan_id' => $this->permintaan->id,
            'nomor_surat' => $this->permintaan->nomor_surat,
            'pengaju' => 'Admin Gudang',
            'pesan' => "Permintaan Anda $kata_status.",
            'url' => route('user.permintaan.show', $this->permintaan->id)
        ];
    }
}
