<?php

namespace App\Console\Commands;

use App\Models\Issue;
use App\Models\User;
use App\Notifications\IssueOverdueNotification;
use Illuminate\Console\Command;

class SendOverdueIssueNotifications extends Command
{
    protected $signature = 'issues:notify-overdue';
    protected $description = 'Send notification emails for overdue issues';

    public function handle(): int
    {
        $issues = Issue::where('status', '!=', 'Closed')->whereDate('due_date', '<', now())->get();
        $recipients = User::role(['Admin', 'Manager'])->get();

        foreach ($issues as $issue) {
            foreach ($recipients as $recipient) {
                $recipient->notify(new IssueOverdueNotification($issue));
            }
        }

        $this->info('Overdue issue notifications sent.');
        return self::SUCCESS;
    }
}
