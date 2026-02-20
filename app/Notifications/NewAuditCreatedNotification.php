<?php

namespace App\Notifications;

use App\Models\Audit;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewAuditCreatedNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly Audit $audit)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Audit Created')
            ->line("Audit '{$this->audit->title}' has been created.")
            ->line("Status: {$this->audit->status}")
            ->action('View Audit', route('audits.show', $this->audit));
    }
}
