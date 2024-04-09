<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Subscription;

class CheckSubscriptionStatuses implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Subscription $subscription;
    protected User $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // get subscriptions and update users.
        Log::info('Run Check Subscription Status Job -- note: this scheduled job needs to be edited to not make so many database writes.');
        foreach (Subscription::all() as $subscription) {
            $user = User::find($subscription->user_id);
            $user->subscriptionStatus = $subscription->stripe_status;
            $user->save();
        }
    }
}
