<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Carbon\Carbon;

class NotifyTrialExpiring extends Command
{
    protected $signature = 'notify:trial-expiring';
    protected $description = 'Notifier les utilisateurs dont l’essai expire dans 10 jours';

    public function handle(): void
    {
        $targetDate = Carbon::now()->addDays(10);

        $users = User::whereDate('trial_ends_at', $targetDate)->get();

        foreach ($users as $user) {
            $user->notify(new \App\Notifications\TrialExpiringNotification($user->trial_ends_at));
        }

        $this->info("Notifications envoyées à {$users->count()} utilisateurs.");
    }
}
