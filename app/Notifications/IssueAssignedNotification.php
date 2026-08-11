<?php

namespace App\Notifications;

use App\Models\Issue;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class IssueAssignedNotification extends Notification
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
            ->subject('Issue Assignment')
            ->line("Issue '{$this->issue->title}' has been assigned.")
            ->line("Due date: {$this->issue->due_date}")
            ->action('View Issue', route('issues.show', $this->issue));
    }
}
