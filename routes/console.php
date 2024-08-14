<?php

use App\Jobs\ArchiveSavedRssFeedItemsJob;
use App\Jobs\CheckSubscriptionStatuses;
use App\Jobs\PurgeOldCacheFilesJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

// Every minute tasks

// Runs every minute to update push data
Schedule::command('broadcast:check-and-broadcast')->everyMinute()->runInBackground();
Schedule::command('mistPush:updatePushData')->everyMinute()->withoutOverlapping(1)->runInBackground();


// Every five minutes tasks

// Takes a snapshot of Horizon every five minutes
Schedule::command('horizon:snapshot')->everyFiveMinutes()->runInBackground();


// Every thirty minutes tasks

// Updates the schedule every thirty minutes
Schedule::command('update:schedule')->everyThirtyMinutes()->withoutOverlapping(30)->runInBackground();


// Hourly tasks

// Deletes queued images every hour
Schedule::command('images:delete-queued')->hourly()->runInBackground();

// Fetches RSS feeds every hour
Schedule::command('fetch:rss-feeds')->hourly()->runInBackground();

// Archives RSS feeds every hour
Schedule::job(new ArchiveSavedRssFeedItemsJob)->hourly();

// Hourly maintenance task: Purges old cache files older than 1 hour
Schedule::job(new PurgeOldCacheFilesJob(1))->hourly();


// Daily tasks

// Purges RSS feeds daily
Schedule::command('purge:rss-feeds')->daily()->runInBackground();

// Expires invite codes daily
Schedule::command('expire:inviteCodes')->daily()->runInBackground();

// Removes old video chunks daily
Schedule::command('video-chunks:remove-old')->daily()->runInBackground();

// Runs ClamAV scan daily at 9:00 UTC (2:00am PDT or 3:00am PST)
Schedule::command('clamav:scan')->dailyAt('9:00')->runInBackground();

// Purges the schedule daily
Schedule::command('purge:schedule')->daily()->runInBackground();

// Checks subscription statuses daily
Schedule::job(new CheckSubscriptionStatuses())->daily();
