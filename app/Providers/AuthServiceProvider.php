<?php

namespace App\Providers;

use App\Mail\VerifyMail;
use App\Models\Channel;
use App\Models\Notification;
use App\Models\User;
use App\Policies\NotificationPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\Model' => 'App\Policies\ModelPolicy',
        Notification::class => NotificationPolicy::class,
//        \App\Models\Movie::class => \App\Policies\MoviePolicy::class,
        \App\Models\Show::class => \App\Policies\ShowPolicy::class,
//        NewsPost::class => NewsPostPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
                ->subject('Verify Email Address')
                ->line('Please click the button below to verify your email address.')
                ->action('Verify Email Address', $url);
        });

        Gate::define('viewWebSocketsDashboard', function ($user = null) {
            return in_array($user->email, [
                'travis@not.tv',
                'admin@not.tv'
            ]);

        });

        // this can be used later when we
      Gate::define('send-chat-message', function ($user, $channelId) {

        // Implement your logic to determine if $user can send a message to $channelId
        // For allowing any user to send to any channel, you can simply return true
        return true;

        // TODO: Setup a database connection between Channel and User to ensure a User
        // can only broadcast on the channel they are on.
        // Implement your logic to check if $user is a member of $channel
//        $channel = Channel::find($channelId);
//        return $user->isMemberOf($channel);
      });

        // this uses the Mail: VerifyMail method and the blade template to send an email.
//        VerifyEmail::toMailUsing(function ($notifiable, $url) {
//            return new VerifyMail($notifiable, $url);
//        });
    }
}
