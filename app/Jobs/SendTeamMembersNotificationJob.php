<?php

namespace App\Jobs;

use App\Events\NewNotificationEvent;
use App\Models\Notification;
use App\Models\Team;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendTeamMembersNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Team $team;
    protected $title;
    protected $message;
    protected $url;
    protected Notification $notification;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($team, $title, $message, $url)
    {
        $this->team = $team;
        $this->title = $title;
        $this->message = $message;
        $this->url = $url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $teamMembers = $this->team->members;

        // Loop through team members and send the notification
        foreach ($teamMembers as $member) {
            $notification = new Notification;
            $notification->user_id = $member->id;
            // make the image the team_poster
            $notification->image_id = $this->team->image_id;
            $notification->url = $this->url;
            $notification->title = $this->title;
            $notification->message = $this->message;
            $notification->save();

            // Trigger the event to broadcast the new notification
            event(new NewNotificationEvent($notification));

        }
    }
}
