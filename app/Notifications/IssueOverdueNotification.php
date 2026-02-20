<?php

namespace App\Notifications;

use App\Models\Issue;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class IssueOverdueNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly Issue $issue)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Issue Overdue Alert')
            ->line("Issue '{$this->issue->title}' is overdue.")
            ->line("Responsible person: {$this->issue->responsible_person}");
    }
}
