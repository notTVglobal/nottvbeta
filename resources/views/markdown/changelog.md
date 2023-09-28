# not.tv Changelog


Last Update: September 27, 2023\
Travis Michael Cross <a href="mailto:travis@not.tv">travis@not.tv</a>

## v0.7.3.6
September 27, 2023
* Added new video player controls
* New popup modal as placeholder for video next and previous buttons
* Updated Schedule mockup page
* Moved pages and features that aren't ready to VIP only views
* Modified the notification icon styling slightly

## v0.7.3.5e
September 26, 2023
* Changed video url from embed code to create a new video model and get the video info using FFprobe.
* When creating a new show episode, if the user adds an external video url a job runs to get the video info.
* Both of the above aren't working in development using Laravel sail. There may be a permissions issue accessing ffmpeg.
* Fixed the chat messages time stamp problem.
* Successfully tested mistserver playback of an external video url using ffmpeg to push the video to a mistserver stream.
* Updated Show page and Show Episode page to playback video url from video model

## v0.7.3.5d
September 25, 2023
* Added user timezone
* Updated the Show Episode edit page to display episode release date and scheduled date in the user's timezone.
* Update the Newest episodes on the Shows page to display the time in the user's timezone.
* Updated the notifications to show the timestamp in the user's timezone.

## v0.7.3.5c
September 24, 2023
* Added loading spinner to channels list
* Changed the layout of the notifications cards
* Show Episode release dates and scheduled release dates are saving 7 hours in the future in the database. This is a bug that needs to be resolved.
* Rss2 News Feeds direct to the wrong page when trying to edit. This needs to be fixed.
* Fixed urls being shared in the chat, they now show up as clickable links, but the colour css style isn't showing  up.
* Fixed the Show copyright year on the bottom of the Show page.

## v0.7.3.5b
September 24, 2023
* Added notification, if a user gets added to a team.
* Fixed how notifications are subscribed to and sent. This now works.
* Added date to notifications, it displays as TimeAgo
* Reversed the order of notifications.

## v0.7.3.4
September 23, 2023
* Added release date to Show Episodes
* Added scheduled release date to Show Episodes. If a user cancels the scheduled release the episode status is changed to Review.
* Setup Notifications System.
* Added notification, if a user gets added to a team.
* Fixed how notifications are subscribed to and sent. This now works.

## v0.7.3.3
September 21, 2023
* Fix notifications button color in light/dark modes.
* Episode statuses are now working. Episodes must be published to display them on the shows page.
* Removed Most Anticipated from the Shows page.
* Fix changelog css
* Improve the way show notes and episode notes are displayed when clicked on in the team and show manage pages.
* Fix Newest Episodes on Show Index Page, no longer displays episodes unless the show is Active.
* Fix copyright year on bottom of Show page.
* Fix Flash Message Modal to clear from the $page.props.flash when the user closes the message. This allows the messages to reappear on the same page if needed.
* Added release date to Show Episodes
* Added scheduled release date to Show Episodes. If a user cancels the scheduled release the episode status is changed to Review.

## v0.7.3.2
September 20, 2023
* Create notifications button.
* Fix channel viewer count
* Fix channel name and video name displaying incorrectly on Stream page.


## v0.7.3
September 19, 2023
* Fix Viewer Count not displaying correctly when watching a channel.
* Fix Watch Now button on the Show page to play if any episode has a video or video_url.
* Added a "The first episode is processing..." banner to the Show page if it has a video but the video is processing.
* Updated the Stats on the Dashboard for Creators to show accurate numbers where available and changed the layout.
* Create notifications button.
* Fix channel viewer count
* Fix channel name and video name displaying incorrectly on Stream page.

## v0.7.2
September 19, 2023
* App loads bitchute and rumble videos from embed codes using ScraperAPI.
* RSS Feeds work, the Feeds controller needs to be removed and replaced with the RSS2 controller.
* More notes will be provided for the next update... 




\
\

    notTV Ltd.
    101-1865 Dilworth Dr, Ste353
    Kelowna, British Columbia
    Canada V1Y 9T1
    
    1-778-363-1411
    hello@not.tv

\
\
