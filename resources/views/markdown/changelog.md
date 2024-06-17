# not.tv Changelog

Last Update: June 17, 2024\
Travis Michael Cross <a href="mailto:travis@not.tv">travis@not.tv</a>

## v0.9.3.30
June 17, 2024

* Fix Movie EditController.
<<<<<<< HEAD
=======
* Add videoPlayer.reset before loading new source for First Play Video change.
>>>>>>> development

## v0.9.3.29
June 17, 2024

* Fix Movie playback button and Trailer buttons to be disabled when there is no video to play.
* Cleaned up the close notifications method for the main notifications when deleting one or all.

## v0.9.3.28
June 17, 2024

* Added a Welcome Modal for Creators.

## v0.9.3.27
June 17, 2024

* Video Upload page share button now opens the share page in a new tab instead of copying the page url to the clipboard.
* Try fixing the video download button on the Video Upload page video table. Needs testing in production.

## v0.9.3.26
June 16, 2024

* Added scheduled command to remove video chunks that are 2 days old or older. This is to remove chunks from uploads that stopped before they were finished.
* Fix the next broadcast Zoom link not showing up during the show.
* Add Zoom Link banner to the Show page.
* The Watch Live button on the show page now goes to the home page for logged out users.
* Responsive Nav Menu slides in.
* Added a copy push destinations feature to the Go Live page.
* The Video on FullPage now shrinks to fit the chat on screen.

## v0.9.3.25
June 15, 2024

* Fix Invite Team Member by Email Server Error.
* Fix Add Show To Schedule duplicate entry (needs testing on production).

## v0.9.3.24
June 15, 2024

* Minor changes, disable some debugging statements.

## v0.9.3.23
June 15, 2024

* Improve the Browse page functionality.
* Change the timing of the display on the Team Show page.
* Change the logic of displaying the next broadcast on the Team Show page.

## v0.9.3.22
June 15, 2024

* Fix the Teams Show page, the Public Message and the Zoom Link.
* Added the Send Email Reminder for upcoming broadcast zoom.
* Re-designed the Team Index page and change it to Browse.

## v0.9.3.21
June 9, 2024

* Fix the Welcome page css layout.

## v0.9.3.20
June 9, 2024

* Fix the expandable description to be a have a custom length.
* Add icons and new styling to the description and news editor TipTap components.
* Add a color picker to the TipTapNewsEditor buttons.
* Fix the spacing of list items in TipTap.
* Change the order of components on the Show Episode Manage page.

## v0.9.3.19
June 7, 2024

* Fix the formatting of dates when scheduling episode releases.
* Refactor the schedule release dateTimes of Episodes on the Episode Edit page.

## v0.9.3.18
June 7, 2024

* Fix the layout problem on logged out reporters pages.
* Added the Browse button to the Welcome page.
* The menu and video controls now stay on screen when the user scrolls on the welcome page.
* Added icons to the menus.
* Users no longer need to confirm their email on first login (need to login 2 more times).
* Switched to the Registration form that appears over the currently playing video to create a seamless experience for first time users watching and registering.

## v0.9.3.17
June 5, 2024

* Fix the syntax for parsing recordings in the MistTriggerController.
* Change the way we handle the ModelID for image uploads to work through the image upload error.

## v0.9.3.16
June 5, 2024

* Refactor how recordings are saved to generate download and share urls.
* Created a UpdateRecordingUrls command to update old recordings.

## v0.9.3.15
June 5, 2024

* Refactor Recordings table on Show Manage page.

## v0.9.3.14
June 5, 2024

* Improvements to page layouts, added settings for News People.

## v0.9.3.13
June 4, 2024

* Building the creator page with a fundraising option. Work in progress.

## v0.9.3.12
June 3, 2024

* Fix the download button on the video upload page.
* Start working on Channel Playlists, and a command to auto update the FirstPlay settings.

## v0.9.3.11
June 3, 2024

* Fix the image upload problem.
* Make removing image EXIF data optional.
* Add Documentary category to Movies Categories.
* Create a video upload preview/download page. 

## v0.9.3.10
June 2, 2024

* Fix the problem with creating an episode with as public domain.

## v0.9.3.9
June 2, 2024

* Style changes to some of the pages.
* Fixing the payment process... one-time contribution needs some work.
* Fixed the Schedule Episode date bug.

## v0.9.3.8
May 29, 2024

* Switch to pusher for websockets.
* Add notifications to users being added, removed or roles updated on news team"
* Fix descriptions not showing bold, italic or underline
* Fix News Story names able to handle some special characters
* Add a News Tip Button to the news pages
* Add a Upload Press Release button to news pages (still needs to be tested)

## v0.9.3.7
May 29, 2024

* Fix to news message broadcast event.

## v0.9.3.6
May 30, 2024

* Added messages to the Newsroom.

## v0.9.3.5
May 29, 2024

* Fix how recurring scheduled dates are calculated, they need to be calculated in the timezone they were created in, then converted to UTC.
* Fix the team members not showing up in the Team Manage page.
* Scheduled a virus scan for 9am UTC.
* Disable scanning images and videos for viruses on upload (it takes too long.)
* Updated add/remove manager buttons on Team Manage page.
* Fix problem with creating a new episode.

## v0.9.3.3
May 29, 2024

* Fix broken PublicNav links.
* Fix Social Share image size, width was still too large.
* Schedule now shows 30 minutes before now so if a show has just ended you will still be able to see it.
* Added a Login to watch banner to Logged out pages.
* Some CSS fixes.

## v0.9.3.2
May 29, 2024

* Fix Social Share image width.
* Fix Ott opening on screen size change. Ott now closes for smaller screens and loads the chat for larger screens.
* Show Episodes now have a public page.
* add TimeAgo to published date in Newsroom.
* Newsroom can now toggle to search and edit published News Stories.

## v0.9.3.1
May 28, 2024

* Removed 6 hour in the future requirement for scheduling shows.
* Fix add to schedule.
* Fix html entities on the share button description.
* Fix array to string conversion error.
* Chat now breaks words instead of all. Fixed the social sharing to not include html entities.
* Fix the Schedule bug, conflict between one-time and recurring shows and newly scheduled shows using the same job as the update schedule batch.
* Added times to Coming Soon shows on the shows page.
* Added virus scanning to uploaded images and videos with a user notification if a virus is found, and exif removal on both images and videos.
* Fixed the image uploader.
* Improved the responsive layout of the news navigation buttons.
* Added encryption to chat messages.
* Added a shadow ban/unban feature for admins in the chat.
* Chat is now open by default in TopRight mode.

## v0.9.3.0
May 27, 2024
* Fixed schedule, improved job handling and added caching, improved the front end layout.

## v0.9.2.5
May 25, 2024
* Fix the logic around storing the nextBroadcast and broadcastDetails for Teams and ScheduleIndex.
* Added schedule caching.
  * Note, the schedule is still slow to load in development with a lot of records. We will need to find another way to prepare the data.

## v0.9.2.4
May 24, 2024
* Layout and Logic fixes.
* Added a Share button, needs testing.
* Fixes to the schedule complete, needs testing.
  * Schedule needs to be automatically cached.
  * Schedule now updates every 30 minutes on its own queue.
* Fixed the display Next Broadcast and Upcoming Broadcasts on Team.show() page.
* Fixed the Zoom Link and Public Message on the Team Page.
* Refactored the Seeders and Factories for new developers.
* Created an Error Code legend, in the Scrum Documentation.
* Upgrade the database from MySQL to MariaDB

## v0.9.2.3
May 23, 2024
* Fix the schedule, not updating correctly (in progress).
* Changed automated schedule update to every thirty minutes.

## v0.9.2.2
May 23, 2024
* Fix 2FA not working correctly in user settings.
* Layout adjustments to ShowEpisode Create and Edit pages.
* Update payment process to use latest version of Cashier.

## v0.9.2.1
May 22, 2024
* Refactor the Show Create and Edit pages.
* Refactor the ShowEpisode Create and Edit pages.

## v0.9.2.0
May 22, 2024
* Upgrade Laravel from 10.x to 11.x.

## v0.9.1.0
May 22, 2024
* Upgrade Laravel from 9.x to 10.x.

## v0.9.0.27
May 21, 2024
* Update the README.md.
* Fix the migrations and seeders for first run.

## v0.9.0.26
May 20, 2024
* Refactor Newsroom and News Stories Table. 
* Rough style the News Categories page.

## v0.9.0.25b
May 17, 2024
* Fixed adding Team Members and remove Team Members from Teams
  Manage page.
* Added emojis to the chat.
* Team members can now be invited directly to the team from the Team Members panel on the Team Manage page.
* Add the News Category and News City pages.
* Make image nullable on news stories table a 2 part migration. (Allow NewsStories to have no image)

## v0.9.0.24
May 17, 2024
* Add unique constraint to Creator slugs.

## v0.9.0.23
May 17, 2024
* Finish editing lock functions, if a user is adding a show to the schedule and closes their browser the lock gets released by the user who is tagged as 'leader' for the purposes of handling function execution', or if no one is on the page, then it gets handled by the next person to land on the page. This is on the Show Manage page"
* Add Show To Schedule should properly handle Recurring Dates now.
* Added slug column to creator table. Generate creator slugs via command.

## v0.9.0.22
May 17, 2024
* Changed the 24 hour requirement for adding shows to the schedule to 6 hours.
* Added a badge to content type in the schedule.

## v0.9.0.21
May 17, 2024
* Fix undefined variable in Schedule.
* Change .gitignore

## v0.9.0.20
May 17, 2024
* Fix route name conflict.

## v0.9.0.19
May 17, 2024
* Moved getModelClass to a Helper function

## v0.9.0.16
May 17, 2024
* Now displays the schedule details after schedule is changed.
* Fixed a memory leak on the Schedule page.

## v0.9.0.15
May 13, 2024
* Add show listener to handle real-time updates of show components.
  * Add Show To Schedule now gets disabled if a Team Member is changing the schedule
  * Fixed the bug where the button was stuck in disabled mode.
  * Added a label to say who is updating the schedule.
  * Added Toast Notifications when using the Add Show To Schedule Modal.

## v0.9.0.14
May 13, 2024
* Fix the shows index page on logged out missing nav bar and footer.
* Some other fixes and minor improvements

## v0.9.0.13
May 13, 2024
* Created a process for handling Creator registrations for existing users

## v0.9.0.12
May 12, 2024
* Add a cookie consent banner
* Make the one-time donations buttons and payment processes work

## v0.9.0.11
May 10, 2024
* Shows are now visible if they are new or active without any published episodes.
* Fixed an Edit Show bug.

## v0.9.0.10
May 10, 2024
* Added a Watch Live button to the show page. It appears if the show is scheduled to be live and there is an active show stream.

## v0.9.0.9
May 10, 2024
* Fixed the schedules not loading one-time shows, fixed duplicating loading too.
* Added a background color to when you hover over an upcoming scheduled show.

## v0.9.0.8
May 9, 2024
* Added a border to image modal X
* Fix broadcast Category on Team nextBroadcast
* Fix Upcoming Broadcast categories not working.

## v0.9.0.7
May 9, 2024
* Now it's fixed! The loading problem for Schedules on logged out mode.
  * Added image modal to Team Logo and Show Poster
* Added X close on image modal

## v0.9.0.6
May 9, 2024
* Fix the way upcoming schedules are displayed
* Fix the loading problem for Schedules on logged out mode.

## v0.9.0.5
May 9, 2024
*   Fix SchedulesService to return the date in the correct timezone (UTC).

## v0.9.0.4
May 9, 2024
* Fixes to the Schedule, it may need some more work. Especially for overlaps.
* Fixed most of the Dark CSS styling.
* Added Zoom Link to Team's Next Broadcast, this will display 30 minutes before a show goes live and stay visible for 60 minutes after the show goes live.
  * Need to build the email notifications to give people a reminder.

## v0.9.0.3
May 8, 2024
* Fix Error Code 789, video playback of uploaded videos.

## v0.9.0.2
May 6, 2024
* Added Next Broadcast to the public Teams page.
* Added caching to the show and movie categories
* Added infinite scroll to the Schedule page.
* Clean up public Show page.
* Clean up controllers to use category and subCategory caching.
* Improve styling and function for public Team page
* Improve description formatting on Show page
* Make show description links possible
* CSS fixes to Team manage page and Team public page.
* Improvements to TipTap, descriptions are now fully editable.
  * The component still needs to be installed across pages.

## v0.9.0.1
May 5, 2024
* Fix the Scheduling problem with broadcastDates not properly saving to UTC and not iterating through the reccurence schedule properly. This is fixed now, plus some formatting fixes on the schedule page.

## v0.9.0.0
May 4, 2024
* Version 1 of the Schedule is now live. This isn't production tested, so we'll see how it goes!

## v0.8.7.85
May 1, 2024
* Fix the UpdateScheduleBroadcastDates jobs.
  * We now have an array of broadcast dates for each schedule
  * The schedule indexes hold the date of the next broadcast for a team.

## v0.8.7.84
April 30, 2024
* Keyboard shortcuts added to VideoPlayer
  * 'J' skips back 1 second
  * 'K' pauses/plays
  * 'L' increases the playback speed each time it is pressed, 1.5x, 2x, 5x, 10x
  * 'Spacebar' pauses/plays
  * 'M' mutes/unmutes
  * 'LeftArrow' skips back 5 seconds
  * 'RightArrow' skips forward 5 seconds
  * 'UpArrow' increase volume
  * 'DownArrow' decrease volume

## v0.8.7.83
April 29, 2024
* Add notTV bug to top left of screen in fullscreen mode.
* Change first play from Admin Settings now broadcasts the event and automatically loads and plays the new video for users that are watching firstPlay.
  * This will need to be tweaked... to only change the video if people are currently watch the 'FirstPlayVideo' stream
  * We can extrapolate this to implement our schedule to automatically change videos in the channel.
* When currently playing video ends we resume playing the channel the user was on. For free users this is the firstPlay channel.
  * This will need to be amended at some point to also start playback at the current time position of the video, depending on the schedule and time of day if the video/stream is not live. This will take some more logic to develop properly.

## v0.8.7.82
April 29, 2024
* Update Live Streaming Guide.

## v0.8.7.81
April 29, 2024
* Update Live Streaming Guide.

## v0.8.7.80
April 29, 2024
* Update Live Streaming Guide.

## v0.8.7.79
April 29, 2024
* Update Live Streaming Guide.

## v0.8.7.78
April 28, 2024
* CSS Fixes.

## v0.8.7.77
April 28, 2024
* Update Live Stream Guide.
* Make it so Team Members can Go Live for a show.
* Minor CSS Fixes to Teams pages.

## v0.8.7.76
April 28, 2024
* Fix Chat Input CSS

## v0.8.7.75
April 28, 2024
* Create a way to change First Play settings easier and start the function in vue. Needs testing.
* Fix chat messages, user profile_photo_path.

## v0.8.7.74
April 26, 2024
* Finish the Public pages for Team.

## v0.8.7.73
April 26, 2024
* Added public pages for schedule, teams index, teams.show and show.show.
* Working on the schedule.

## v0.8.7.72
April 26, 2024
* Continue working on logged out pages.
* Fixed Recording playback issue, we weren't creating the MistStream for auto recordings. Now we are.

## v0.8.7.71
April 25, 2024
* Logged out version of pages are now live.

## v0.8.7.70
April 25, 2024
* Fix the logo, something was breaking the login page.

## v0.8.7.69
April 25, 2024
* Fix the Recordings prefix for playback.
* Created a Live Streaming Guide.

## v0.8.7.68
April 24, 2024
* Fix the Team ShowEpisode Search to return results newest first.

## v0.8.7.67
April 24, 2024
* Cleanup the SearchController and SearchService.
* Remove the attempt at lazy load which prevented the Team ShowEpisode search functionality.

## v0.8.7.66
April 24, 2024
* Fixed the recordings. These should be working now.
* add episode search function to Teams.show() page.
  * In the process of getting lazy loading working on the search results.

## v0.8.7.65
April 23, 2024
* Change how recordings are saved. Added the ability for admin to change the recording path in the Admin/Settings.
  * User recordings are now saved in a recordings_user folder.

## v0.8.7.64
April 14, 2024
* Rebuilt the way scheduling is handled. We have new Scheduling Jobs.
* A shows broadcast schedule is now processed and stored in the schedule.

## v0.8.7.63
April 14, 2024
* Changed the schedule column name for scalability and solve some problems.
* Added a public message to the Teams page.
* Added buttons for Team managers to more easily find and return to the Team Manage page.
* Added social media links to teams.
* Hid creators/credits from teams, shows and episode pages.
* Creators have a toggle to choose if they want their profile to be public.
* Revised broadcast schedule logic.

## v0.8.7.62
April 11, 2024
* Fix recordings not working when user Start Recording.
* setup more debugging statements.

## v0.8.7.61
April 11, 2024
* Setup an environment variable for recordings.

## v0.8.7.60
April 11, 2024
* Move user_recordings to another folder.

## v0.8.7.59
April 11, 2024
* Fix recording download problems by creating a new folder for user initiated recordings vs system auto push recordings.

## v0.8.7.58
April 11, 2024
* Attempted fix of download files by switching urlencode() to rawurlencode().

## v0.8.7.57
April 11, 2024
* User generated recordings fail when trying to download. But the automatic recordings download OK. This is true on the development side, not sure about production.

## v0.8.7.56
April 11, 2024
* Added Start Recording and Stop Recording ability on Go Live page.
* The recordings list on the Show Manage page now has download buttons for the recordings.
* Recordings that are created by the Auto Push feature are labelled as "automatic recording" in the recordings list.
* Fixed the Log Out button to run 1 method instead of 2.

## v0.8.7.55
April 9, 2024
* Schedule is working, tested with overlapping shows.

## v0.8.7.54
April 9, 2024
* Working on the schedule
* Schedule page is now working. Just finishing the styling, need to test with overlapping shows, conflicts and different lengths of shows.

## v0.8.7.53
April 8, 2024
* Fixed the login modal width and added a bg gradient.
* Hide the movies sections 'new releases' and 'coming soon', and the links at the top.
  * This is temporary until we have more movies.

## v0.8.7.52
April 6, 2024
* Minor adjustment to the Login css, width.
* Fix contribution page.

## v0.8.7.51
April 6, 2024
* Update the Contribution Page.
* Update the Upgrade panel and which OTT components are visible.

## v0.8.7.50
April 6, 2024
* Update the RSS Feeds to display all temporary feed items on one page.
* Added button styling to the Newsroom header buttons.
* Added a News Districts page.
* Added an Interactive Map of Canada to the News Districts page.
* Added an Example Subnational Districts map to Alberta.
* Added a National Districts map to Alberta.

## v0.8.7.49
April 5, 2024
* Adjust FetchRssFeedJob to handle images in the media:group tag.

## v0.8.7.48
April 5, 2024
* Create dedicated cloud folder for video uploads that are Creator user video uploads.
* Mount Spaces to Mistserver to test creating streams from S3.
* Add new cloud_private_folder as database column to AppSettings and Videos.

## v0.8.7.47
April 3, 2024
* Remove Toast Notifications for 'Push Destinations Updated' and 'Stream Info Loaded'.
* Updated Terms of Service.
* Update error pages.
* Team Leader, Managers and Show Runners can now change the Published Date on Show Episodes. (This will be temporary up until the blockchain registration comes into effect).
* Adjusted Add Show To Schedule, must be 24 hours in the future (not just tomorrow or later). This is to ensure time for promoting shows on notTV.

## v0.8.7.46
April 3, 2024
* Hot fix: 'full_push_uri' column type changed to text. To fix 500 error.

## v0.8.7.45
April 1, 2024
* Update Whitepaper.

## v0.8.7.44
April 1, 2024
* Playback a recording now displays the recording name in NowPlayingInfo.
* OTT TopRight now defaults to Info.

## v0.8.7.43
April 1, 2024
* Update the Go Live Page refresh settings to be every 15 seconds.
  * The video now reloads only if the stream is offline and goes online.

## v0.8.7.42
April 1, 2024
* Fix the recording bug.

## v0.8.7.41
April 1, 2024
* Fix the recording bug.

## v0.8.7.40
March 31, 2024
* Use the MistServerService to fetchStreamInfo.
* Fixes to the Add Push, Remove Push, fetchPushDestinations and Background Fetch methods.
* Start troubleshooting the recordings bugs. 

## v0.8.7.39
March 31, 2024
* Fix Recording Unique error.

## v0.8.7.38
March 31, 2024
* Cleaned up Push Destination Form console log and GoLive Store console log messages.

## v0.8.7.37
March 31, 2024
* Fix duplicate recordings bug.
* Fix fetchStreamInfo().

## v0.8.7.36
March 31, 2024
* Remove debugging lines from MistPushCommand.
* Change the way fetchStreamInfo handles the Server Uri.

## v0.8.7.35
March 31, 2024
* Add additional line to the environment for mist server.

## v0.8.7.34
March 31, 2024
* Update the Push Destinations component on the GoLive page to use the new MistServerService Factory.
  * The Push Destinations are refreshed on the page through a backroundFetch process every 60 seconds.
  * The Push data is stored in a Cache and update every 60 seconds on the server through a scheduled command.
  * The next step for this feature is to optimize the MistServer calls to not have to do an Authorization Challenge on every request.
    * Send the updates to the creators in real-time using the Web Socket server. Will need to plan a strategy for the broadcast channels.
  * Facebook is a bit buggy, if you stop and start pushes it doesn't allow the next push to go through.. need to start/stop one more time. And too many starts/stops seems to lock you out.

## v0.8.7.33
March 30, 2024
* Rebuild the MistServerService to handle multiple Mist Servers.
  * Added MIST_PUSH_*
  * Added MIST_PLAYBACK_*
  * Added MIST_VOD_*
* Fix the MistServerService send().

## v0.8.7.32
March 29, 2024
* Video Recordings on shows can now be played back.
* Added a share button to the video recordings which copies the url to the stream playback page.
* Added modals for the download, save to premium and add to episode buttons.

## v0.8.7.31
March 29, 2024
* Add refreshing loader icons on the Push Destinations page.
* Added a Disable All Auto Pushes button.
  * Having some challenges with removing single auto pushes.
* Start push and End push should be working and Enable Auto Push.
* Push destinations refreshes every 10 seconds. This will need to be optimized to just have the server do the refreshing, cache the data for the client.
* Changed the colour of the TopRight Chat background and Now Playing Info background.
* Fixed the Websocket bootstrap settings.

## v0.8.7.29
March 28, 2024
* Fix the GoLive Component to fetch push destinations in a new way.
* The Push Start and Push Stop buttons seem to be working.
* The Enable Auto Push seems to be adding it to the MistServer.
* Disable Auto Push does not work.
* Disable All Auto Pushes does not work.
* Added Toast Notifications to Push starts/stops.
* Added a mist_server_auto_pushes table to track auto pushes and make removing individual pushes easier.
* Added a mist_server_active_pushes table to track active pushes.
  * This table can get updated every n seconds through a scheduled command.
  * The GoLive component can make an api call to retrieve the data that is updated by the command to stay up-to-date.
  * This is the most efficient way to handle polling the MistServer for accurate push data.
* There is a bug in the mist server... The trigger settings with blocking needs to be just right.
  * Additionally, if a recording is started and then stopped right away and the Stream Resume is enabled then the recording will not start again.

## v0.8.7.28
March 28, 2024
* Fixed the type hints for the MistTriggerController functions.
* Update Log debug messages for testing in MistTriggerController.

## v0.8.7.27
March 28, 2024
* Turn on all debugging logs for Mist Push Triggers (Start, Stop, End Record).

## v0.8.7.26
March 28, 2024
* Add /stats path to redirect to the current public analytics page.
  * Added an app_setting for the Admin to easily change this in settings.
* Change the color of the disable auto push button to make it easier to see when push is enabled.
* Add a Live Analytics button on the Go Live page that opens a new tab with the live analytics of notTV.
* Add debug lines to the MistTriggerController, handleRecordingEnd.

## v0.8.7.25
March 26, 2024
* Fix FirstPlay and Video Player problem.
  * Aux video player is working on Go Live page.
  * First Play video on Main Player is working.
  * Channels with Mist streams are working.
  * Start Push to destination on Go Live page is working.
  * Stop Push to destination on Go Live page is also working.
  * Add Auto Push button works.
  * Remove Auto Push button IS NOT WORKING!
  * Event Broadcast to Go Live Page to update the Push button status IS NOT WORKING!

## v0.8.7.24
March 26, 2024
* Add back in the Cache::forget(key) for First Play settings.

## v0.8.7.23
March 26, 2024
* Overhaul the MistTriggerController.
* Cleaned up the styling of the GoLive page.

## v0.8.7.22
March 26, 2024
* Fix Invite Code Registration validation mistake, where a failed validation was not being returned back to the user.
* Added a better Invite Code check on Registration submission.
* Moved the LIVE badge and ViewerCount to Channel 2 (Live).
* Added a TotalWatching count to Channel 2 to account for users who may not be logged in and watching.
  * This does not take into consideration if people are watching a different channel.. it's not currently functional and will need to be developed with additional considerations.
* Enabled the OTT Channel and Playlist buttons for all users.

## v0.8.7.21
March 26, 2024
* Hot Fix
  * Create a one-time command to get the FirstPlaySettings from the existing AppSetting columns into the new FirstPlaySettings Json column.
  * $ php artisan settings:migrate-first-play

## v0.8.7.20
March 26, 2024
* Change Channel Footer message.
* Create a FirstPlaySourceSelector component in Admin/Settings.
  * Toggle between a custom video source or a channel as the first play source.
* Modify the First Play Json File (cache) and add FirstPlaySettings Json column to the AppSettings table.
* Added Toast Notifications
  * Timeout defaults to 3 seconds and the notification will fade out.
  * Set ('message', 'status', 'customTimeoutValue')
  * Status: success, error, info, warning

## v0.8.7.19
March 25, 2024
* Adjust style on Navigation Menu.
* Restructure the Admin/Channels page and components.

## v0.8.7.18
March 24, 2024
* Fix Team->team_leader where creators(id) on delete was nothing, now it's null.

## v0.8.7.17
March 24, 2024
* Add invite code check (inviteCodeStore) on the creator registration page
* Fix bug where Creators not being redirected to Dashboard on route '/'
* Fix the styling of profile images for Team Members on the Team Manage page
* Fix the styling of Show images on the Show Manage page
* Fix the Show Runner, it consistently uses the creator_id now
  * The create new show form now pre-loads with the logged in creator as show runner
  * Show Runner drop down now displays all of the possible show runners.
  * If a Team member's account is Frozen or Suspended they will not appear in the Show Runner drop down.

## v0.8.7.16
March 24, 2024
* Added a video in a pop-up modal on the creator registration page

## v0.8.7.15
March 24, 2024
* Fix the GoLive button on the Dashboard to appear if the user canGoLive instead of only for teams the user owns
* Disabled extraneous Log messages in MistServerService

## v0.8.7.14
March 24, 2024
* Add Invite Code Settings to App Settings
* New Content Creators automatically get 2 creator invites and 10 audience invites
* Changed the Upgrade button to a Contribute button
* changed the upgrade page to the contribute page and completely changed the styling
* changed the route definitions for the subscription payment from shop/subscribe to contribute/subscription
* will need to create contribute/donate for one-time payments and to support favourite shows

## v0.8.7.13
March 23, 2024
* Added register Creator process.
* Hide Go Live button on Dashboard if Creator has no Teams to avoid the error message.
* Remove verification of postal codes (not working right now)
* Creators now land on Dashboard after email verification
* Creator receives Welcome email after registration
* A News Story can now be deleted by the creator, producer or assignment editor if it's rejected, not just if it's new

## v0.8.7.12
March 23, 2024
* Fixed profile image minimum height and width on Team page and Show page
* Team Leaders can now remove Team Members instead of only Team Creators
* Invite Creator form and email now working
* Creators now see invite codes from the button in their dashboard that lets them invite other creators and audience members.
* Added a last_login_at dateTime to user table
* Fix the NewsFeed pages when you change the page it will load the next page from the top instead of the middle.
* Styling adjustments to the user profile for admin.

## v0.8.7.11
March 22, 2024
* Fixed the background height on the logged out pages to cover the full page.
* Added image placeholders to Creators who haven't uploaded an image yet on Show.show and Team.show pages

## v0.8.7.10
March 22, 2024
* Changed the Purge old RSS Feed Items command to remove items that are older than 30 days instead of 7 days.
* Fix the ExpireInviteCodes command, needed to return an Int.
* Enabled Two-Factor Authentication.
* Fixed the Change Password 405 Method Not Allowed error.
* Add Twitch to Push Destinations.
* Add Ping as a placeholder image for Creators with no profile image.
* Fixed News Story Edit
  * If a province was selected as the location it would default to the first city in the province, this is fixed it defaults to the selected province instead.
  * Added the Newsroom button
* Added the Newsroom button to the News Stories page and to individual News Stories
* Fixed the policy for changing a News Story status and publishing a News Story.

## v0.8.7.9
March 20, 2024
* Fix the recordings table, bytes_recorded and total_milliseconds_recorded need to be a BIGINT not an INT.

## v0.8.7.8
March 18, 2024
* Disabled the Go Live and Start Recording buttons on the Go Live page as these are Premium Creator Features that haven't been developed yet.

## v0.8.7.7
March 18, 2024
* Recordings for shows now get listed on the Show Manage page.
  * Next, we need to set up a job to include in the UpdateRecordingModelAndNotify Job to create a MistStreamWildcard for the recording to allow the user to playback the recording. 

## v0.8.7.6
March 17, 2024
* Changed the MistServer Trigger for Recordings to only trigger when a Recording Stops.
  * Updated the recordings table to more accurately reflect the data returned from MistServer:
    * total_milliseconds_recorded, instead of duration
    * bytes_recorded, instead of file_size

## v0.8.7.5
March 17, 2024
* Updated the log outputs for recordings.
* The MistServer needs the Triggers set to "Blocking" on production, otherwise they aren't triggering the API call.

## v0.8.7.4
March 17, 2024
* Updated cloudflare rules to allow the api access from the mistserver triggers.
* Fixed the filepath of the mist triggers to allow query strings if present.
* Adjusted how it gets the file extension in the case of query strings.

## v0.8.7.3
March 17, 2024
* Added a recordings table, and updated the MistTiggerController to handle new recordings.
  * Send them to /api/mist-push-handler
  * The mist-push-handler is setup to check the X-TRIGGER header and determine what kind of trigger it is to process accordingly.

## v0.8.7.23
March 17, 2024
* Change delete queued images to hourly (was set to everyMinute for testing purposes)

## v0.8.7.22
March 17, 2024
* Change video poster to video image_id
* Add index to image_hashes hash column
* Add image_id to products table

## v0.8.7.21
March 17, 2024
* Add a job to check a newly uploaded image against the existing image hashes. If it's a duplicate it will update the associated model and queue the image for deletion. Currently the duplicate images are queued for deletion every hour.
* Update generic image uploader for Admin to handle duplicate images and test the image hash, deletion process.


## v0.8.7.20
March 17, 2024
* Improve Hash image, remove duplicate job logic to avoid race conditions.

## v0.8.7.19
March 16, 2024
* Troubleshoot and attempt to fix the failed job error:
  * App\Jobs\UpdateImageReferencesJob has been attempted too many times or run too long.

## v0.8.7.18
March 16, 2024
* Slight adjustments to the commands and jobs.
* Changed RssFeed jobs to update feeds every hour, archive feed items every hour and purge old feed items every month.

## v0.8.7.17
March 16, 2024
* Create three commands to purge duplicate images from the database in preparation to update the ImageController to reference existing images instead of uploading duplicates.
  * php artisan images:hash
  * php artisan images:update-references
  * php artisan images:delete-queued

## v0.8.7.16
March 16, 2024
* Add NewsRssArchive page
* Fix the way items get archived

## v0.8.7.14
March 15, 2024
* Fix Archive RSS Feed Item 500 Error

## v0.8.7.13
March 15, 2024
* Fixed the NewsRssFeed Policies
* Changed the purge News RSS Feed from daily to monthly

## v0.8.7.12
March 15, 2024
* Added How To Push To Facebook Training page
* Other fixes
* Start Push and Stop Push buttons are working.

## v0.8.7.11
March 8, 2024
* Fix the News Story edit/ Location search and select function
* Added News Person roles
* Added policies for creating new stories, editing and publishing
* Added the schedule to the schedule page.

## v0.8.7.10
March 6, 2024
* Fix Episode Watch Now... remove the episode.status = published requirement to display watch now button. This means creators can preview the episode page more accurately before it's published and gets rid of the bug of the watch now button not displaying.

## v0.8.7.9
March 6, 2024
* Add migration to backfill Ulid's on Show Episodes.

## v0.8.7.8
March 6, 2024
* Add database migration to add Ulid's to all existing shows that don't have one.
* Fix the News, News Story, Reporters page view when logged out and logged in.
* Fix team notes not editable on the team manage page
* Remove 1 second sleep when saving notes on team manage and show manage pages.

## v0.8.7.7
March 2, 2024
* Changed the way we get UserData into the UserStore. This may break some things, but it should improve the app reliability and performance.
* Left off trying to fix the Push Destinations, the MistStreamPushDestinationForm Modal is used for both Adding and Editing Mist Stream Push Destinations, but this doesn't follow the best practices to keep things simple.

## v0.8.7.6
March 1, 2024
* Some changes to the invite code manage page

## v0.8.7.5
March 1, 2024
* Made the 'get an invite' code button on the register page easier to see.
* The Show Runner can now see the shows they are running on their dashboard, and they can edit and schedule the show they are running.
* Fixed the Add Show Runner modal still showing up, even if there was a Show Runner on the Show Edit page.

## v0.8.7.4
March 1, 2024
* Fixed the can't change the show category without a show runner error to display a message to the user to set the show runner.

## v0.8.7.3
February 29, 2024
* Fixed the Password Reset, Forgot Password and Verify Email logic
* Fixed the page styling for the Verify Email, Reset Password and Forgot Password pages.
* Invite codes now will register the proper user type. Creator invite codes can only be used on the Creator registration page.

## v0.8.7.2
February 28, 2024
* Updated the Invite Code system to include expiry dates, batches and role specific invite codes.
* Started a Creator Registration process that uses creator specific invite codes.

## v0.8.7.1
February 27, 2024
* Add RTMP Push Destinations
* Add functionality to the goLive page
* Update the Admin Settings page

## v0.8.6.6
February 22, 2024
* Create a ScheduleStore to fetch and save the schedule.
  * Built a Month and Day view for the schedule on the Admin/Schedule page. We can use this component for the public/users schedule page.
  * Added Admin functions to purge the schedule cache. The cache is invalid after 15 minutes and users will get fresh data every 15 minutes.
  * Built a javascript log to determine if the viewer should fetch fresh data from the database when browsing the schedule. This will eventually be replaced by real-time features.
  * Started to build the Creator's Add Show to Schedule Modal.
* We have a need for Closed Captioning. This is an important accessibility feature.
* Still need to finish the Show recordings feature.
* Integrate OAuth login for people if it adds substantial convenience and we can keep the app secure.
* If we register with Zoom we can integrate Zoom calls easier for our Creators.
* Need to implement a Modal into the Schedule that allows the user to 1) subscribe to the show / aka follow the show, 2) Go to the show page, 3) Set a reminder for when the show goes live, 4) go back 

## v0.8.6.5
February 19, 2024
* Updated the GoLive component, it works for Shows and ShowEpisodes with unique stream keys for each
* Added a clipboard to the stream keys to make them easier to copy
* Create a /training/go-live-using-zoom page

## v0.8.6.4
February 15, 2024
* Changes are now getting pushed from our development branch to our staging branch then pulled into master (production).
  * See our git log for more details.
* Admin can now edit streams and add metadata (optional parameters) to streams
* We are testing a 72 hour DVR system on our main live channel. 
* Added GoLive back to the Show Manage page.
* Updated the goLive page to allow the user to choose a valid show to goLive on.
  * The unique stream key is displayed for the show.
  * Added a mockup "Go Live Now" button, this will be a premium creator feature where they can go live on their show page and/or to other push destinations. This is more like a streamyard service and outside our core offering.
  * Added a mockup countdown until live. When a show is scheduled this will count down until the show gets pushed to the channel. This allows the creator to pre-connect and check the stream conditions/quality.

## v0.8.6.3
February 12, 2024
* Added the ability for Admin to create new streams and remove streams.
* Added a general service notification for videos that have an error with playback.
* Added the Episode ID (Ulid) to the bottom of the Now Playing Info panel.
* Created a MistServerService class for handling connections to the MistServer.

## v0.8.6.2
February 11, 2024
* Updated the Home page
* Added a contact form, moved the newsletter signup to its own page on our site.
* Redirect the /subscribe link to our newsletter signup page.
* Fixed playing external videos.
* Added Date and Episode Number to Now Playing Info Box.

## v0.8.6.0
February 9, 2024
* Channel scheduling system has gone through some big updates. It's a work in progress, however, and while we can choose MistStreams for channel playback we still need to creat the modal form to Add new MistStreams.
* Added a Feedback Form button that floats across all pages for logged in viewers
* Increased the @media screen base px size on 1024-1920px width screens.
* Other changes... see git log commit history for more info.

## v0.8.5.0
February 5, 2024
* There may be some challenges migrating this to production.
  * Some of the table migrations got a little funky while building the show schedule
  * MistStreams now use Ulid as Primary Key.
  * Created a Show Schedule table and Controller with a grid layout on the Creator Dashboard.
    * It stays up-to-date and shows 6 hours across 5 days
    * The data is cached in a local file and stays valid for 15 minutes.

## v0.8.4.1
February 1, 2024
* Added creative commons licensing to movies and show episodes
* Created new "new" badge for movies and shows
* Optimized the database loading on the Shows index page
* Added the option for Creators to choose whether the first episode played from the Show page when the user clicks "Watch Now" is the oldest episode first or the newest episode first.

## v0.8.4.0
January 29, 2024
* Added show sub-categories
* Fixed some policies (movie and show)
* Re-designed the NowPlayingStore
* Created a First Play Data Cache to store the First play video data in a .json file
  * Added a "clear cache" button to the admin settings
  * The data is saved in the database AppSetting as well as in the /js/firstPlayData.json

## v0.8.3.8
January 29, 2024
* Styling change to the stream page! Now when you click the video it hides the osd (on screen display) instead of pausing/playing.
* Movies now have categories and sub-categories.
* Movies have statuses, and must be switched to 'Active' before they are viewable from the movies page.
* Movies and NewsStories can have a status of "Creator Only" and only creators will be able to see that content.
* Active movies are only viewable by users with an active subscription or vip.
* Admin can see movie statuses from the Admin settings.
* News Story statuses can be changed from the Newsroom. If a story is published it no longer shows up in the NewsRoom Story Table and will immediately appear in the public News Stories list.
* Fixed the Chat, it's working again.
* Fixed the Channels, they seem to be working, but need to be tested.
* The NowPlaying Info needs to be tested and tweaked.
* Fix the layout of the NowPlaying Ott panel.
* Movie Release Schedule needs to be created... Movies > Coming Soon will display movies with a release date of at least 24 hours in the future.
* The videoPlayer playback bar has disappeared and needs to be fixed.
* The Movie and Show and ShowEpisode Watch Now button is missing or disabled and will need to be fixed.
* Notification don't appear to be working and will need to be fixed.
* Need to add Movies to the Teams page.
* Need to allow Teams to create movies.
* The VideoPlayer FullScreen button isn't working and needs to be fixed.
* The upgrade ott panel styling is messed up and needs to be fixed.
* The Public News and Newsroom need the following pages: Categories, Citites, Districts, Press Releases, Archive
* Need to add Change News Story Image capability.
* Need to add Categories and Subcategories to Shows.
* Need to add Edit ShowEpisode description directly on the showEpisode Manage page.
* Create a channel manage page where the admin can add shows, episodes, videos and movies to the time slots.
  * Shows, movies , showEpisodes, News stories can be a one-off
  * Shows can be recurring
  * Set live or on demand
  * Creators get a reminder before their scheduled live time slot. 3 misses and they lose their slot.
* We need to schedule our town hall meetings

## v0.8.3.6
January 27, 2024
* Removed the VideoJS YouTube extension because it has a tracker built into it.
* Added new text editing options to the News Story Create and Edit pages.

## v0.8.3.5
January 26, 2024
* Finish the News Story Edit Page.. need to fix the News Story Create Page to match.
  * The TipTap Text Editor is not loading ... work in progress..
  * Almost got it working...
* News Create and Edit pages are done.

## v0.8.3.3
January 25, 2024
* Major overhaul of the Newsroom and the News page.
* Categories, sub-categories, and cities are all working with News Stories.
* The search on the News page will search by story name, story content (needs to be tested, might be resource intensive), news person, city, province, category, sub-category, or published date (YYYY-MM-DD).
  * If searching by date, you can search by year (YYYY), year and month (YYYY-MM), or the full date (YYYY-MM-DD).

## v0.8.3.2
January 24, 2024
* Fix the News seeders
* Added python scripts to parse external datasets into csv for Canada's Federal Electoral Districts

## v0.8.3.1
January 23, 2024
* Create News Sub-categories.
* Filled the database (seeders) with News Sub-Categories, Movie Sub-Categories, and News Sub-Categories.
* Created new models for NewsCities, NewsProvinces, NewsPostalCodes, News MLA Ridings, News Federal Ridings
  * Need to populate the Cities, PostalCodes, MLA and Federal Ridings tables.
* Seed the News Tables
  * Canadian Cities and Towns, source: https://natural-resources.canada.ca/earth-sciences/geography/download-geographical-names-data/9245
  * Canadian Postal Codes, source: https://github.com/djbelieny/geoinfo-dataset
    * Note: Stats Canada used to provide this dataset but discontinued it in June 2017. Canada Post now charges a fee for a regularly updated dataset.
  * Canadian Electoral Districts (Federal Ridings)
* Side Note: Additional Stats Canada Datasets can be found here: https://www.statcan.gc.ca/en/microdata/dli/data/all

## v0.8.3.0
January 22, 2024
* Fixed the News RSS Feed
  * All feeds now process images
* Added Search to the News RSS Feeds
  * Search is by title or description
  * The main feed list is now ordered by the newest updated feed first
* Added Archiving to the News RSS Feed
  * The feeds are updated every hour
  * They are purged every 7 days
  * Archived items will remain indefinitely.
  * Feeds display a new 'updated time' if they successfully add new feed items.
* Update the DatabaseSeeder and FirstRunSeeder for new developers
* Added a ShopSeeder
* Added a MovieSeeder
* Added News Categories to the news_categories table in the database, to be added to production through the NewsCategorySeeder
* Added Movie Sub-Categories to the movie_category_subs table in the database, to be added to production through the MovieCategorySubsSeeder
* Added Show Sub-Categories to the show_category_subs table in the database, to be added to production through the ShowCategorySubsSeeder

## v0.8.2.1
January 21, 2024
* Fixed the News Page routes
* Rebuilt the Newsroom home page
* There is a bug when refreshing the page on any of the public news or news reporter pages, if the user is logged in the video is playing but it's muted (firstPlay) and hidden.
  * Another design bug, the topDiv function doesn't seem to work on these page either.
* Fixed the newsRssFeeds routes
* Added a :url prop to the BackButton and CancelButton so you can optionally pass a custom route that the button will use, e.g., \<BackButton :url="'/newsRssFeeds'"/>

## v0.8.2.0
January 19, 2024
* Fix the page layout/loading bugs on the login, register, password reset and password confirmation pages
* Improve
* Increased Password Validation Rules to:
  * Minimum 10 characters
  * Uncompromised (can't have been in a data leak)
  * Requires a mix of Letters, mixedCase, Numbers, and Symbols
* In the middle of updating the Verify Email routes ... redirecting them to /public/{routeName}
  * This includes the Password Reset pages
* Fix the VideoPlayer on click/tap functions. If in TopRight mode the video will go to FullPage mode. If in FullPage mode it will pause/play the video. If in Full Page mobile mode it will toggle the controls show/hide.
  * There is still a bug with the mobile devices where only the top half of the video is clickable/interactive.
* Rebuilt the News Controllers (major update)

## v0.8.1.3
January 18, 2024
* Fix the page layout/loading bugs on the login, register, password reset and password confirmation pages
* Improved the formatting of the login and register components.
* Added 'layout' to the appSettingStore to allow an easy way to turn off AppLayout on specific pages that need it.
* Created a Welcome page full reload function to get the video player to restart in certain circumstances
* Created a noLayout navigation header
* Created a new style for Public News pages
* Added the News Reporter Index page
* Added the footer to all of the public pages.
* Added a link to the Whitepaper in the footer
* Made the Changelog private, only registered users can access it.
* Created Public News Navigation Buttons


## v0.8.1.2
January 17, 2024
* Finish codebase-restructuring
* Implemented simpler logic in the Vue Templates with the PageSetup and the AppSettingStore
* Fixed the Back and Cancel button navigation
* Fixed the Paginator, scrolls to top on button click bug
* Cleaned up code formatting throughout the project
* Cleaned up the styling of the Shows Manage and ShowEpisode Manage pages
* OTT Chat and Channels are still not working.

## v0.8.1.1
January 16, 2024
* work on Ott panels.
  * Filters and Playlists open and close
  * Now Playing Info opens and populates with Show data
* Troubleshooting video uploads where the UploadVideoToSpacesJob wasn't adding the video_id to the show_episodes table.
* Created a New Listener to SendVideoUploadedNotification.
  * Send a Video Uploaded Admin Notification Email
  * Send a New Notification Event to the user who uploaded the video (to appear in their navBar notifications)
* Created a Services/NotificationService to simplify sending users a new notification.

## v0.8.1.0
January 16, 2024
* codebase-restructuring
* This is a major overhaul of the folder structure and how the OTT components work.
* The Ott panels still need work.
* The Chat needs to be fixed.
* The Back and Cancel buttons now work better than ever.
* The code is lighter, we introduced a Utilities folder:
  * PageSetup.js will save time modifying code that loads common to every page.
  * StoreReset.js makes sure the Logout button resets all of the stores.

## v0.8.0.8
January 16, 2024
* Cleaned up code.
* Created a new folder structure for Components.
* Rebuilt the Chat Component
* Combined the FullPage and TopRight Ott Panels into one component

## v0.8.0.7
January 15, 2024
* Fixed the mobile chat layout with PiP Chat Mode.
* Click video in FullPage mobile to show/hide video controls. There is a bug right now where the user has to click or touch the top portion of the video to make it work. In FullPage desktop mode click the video to pause/unpause.

## v0.8.0.6
January 15, 2024
* Started to add the base blockchain functionality in the database.
* Created a feature to transfer a team to a new owner. This feature is still in progress, need to build the notifications and the way for the user receiving the transfer to accept or reject it. Later we'll add the ability to link it to the blockchain.
* Fixed some permissions errors. Now only a Team Owner or Team Leader can make a new manager. Managers can add new members. Only Team Owner or Team Leader can Edit the Team. Only the Team Owner can transfer the team.
* Cleaned up ShowPolicy to remove duplicate code.
* Cleaned up TeamPolicy code.
* Any Team Member can be made a Team Leader.
* Created a Creator Dashboard Notification Panel. This will display live updates when new shows are playing as well as live notifications from the community and newsroom and weather.

## v0.8.0.5
January 14, 2024
* Fix the create new Team bug. And add a validation to check if the creator status is active.

## v0.8.0.4
January 14, 2024
* Added the <a href="https://not.tv/whitepaper">notTV Whitepaper</a> to the list of links on the footer of the home page.
* Fixed the Dark Paginator, it was showing the wrong page numbers on the buttons.
* Added new Admin/Settings for the MistServer

## v0.8.0.3
January 14, 2024
* Only the 1st channel (Live) will show the active viewer count. The viewer count will be temporarily be hidden for the other channels.
* Added a "scroll down" indicator to the channels menu.
* Created a custom scrollbar (.scrollbar-custom) class
* Updated the <a href="https://not.tv/whitepaper">notTV Whitepaper</a>.
* Added height:100% to html and body (trying to fix the .md display bug)
* Changed the base font size for larger screens.
  * 1920px+, 18px
  * 2560px+, 24px
  * 3840px+, 32px
* Changed some styling on the Creator Dashboard
* Fixed a bug on the MyTeams element on the Creator Dashboard
* Started some styling changes on the Team Manage page.
* Changed the CSS styling of the Team, Show and Episode Manage pages to make it more obvious when you are on a different manage page. Each page now has its own colour. Orange (Team Manage), Blue (Show Manage), Green (Episode Manage).
* Cleaned up the code on the ShowEpisodeManage page.

## v0.8.0.2
January 12, 2024
* In the last update, a new log feature was added to send notifications to our Log and Slack when new videos are being uploaded.
* OSD (On Screen Display) now hides when any of the OTT (Over-The-Top) panels are open on FullPage Video mode.

## v0.8.0.1
January 12, 2024
* CSS cleanup. Hide scrollbars.
* Darkened the background of chat messages on Full Screen Desktop.
* Created a NowPlayingStore to hold the Now Playing Info.
* Now Playing Info displays on FullPage in the top left
  * Show Name
  * Episode Name
  * Movie Name
  * File name
  * The names are clickable and will take you to the show, episode or movie page.
  * File name is only displayed if you play the video back from the video upload page.
  * (web) is displayed if the video is playing back from an external source.
  * Channel Name is displayed when a channel is selected, along with the active viewer count
  * A live badge will appear in the top right when activated for live streams. This feature still needs to be completed. The badge exists is all.
* Fixed the navigation so the Back button on the FullPage Video actually goes back.

## v0.8.0.0
January 12, 2024
* Fix the PiP bug on mobile (isMobile):
    * The Chat had a bg-gray-800 which was covering the PiP Video. Removed the bg color.
    * Made the PiP video the full device width and removed the opacity 80% setting.
* Fixed the chat layouts across all screen sizes. The secret to getting the height right was in using position: fixed, max-height: calc(#vh - #rem), bottom-#. Bottom is a tailwind property, the others are traditional CSS. Plus, using z-index so the overflow is hidden under the video, ottButtons and navbar.
* Create an AppSettingStore for managing global UI settings like background color, theme mode and other dynamic styles. This store can be extended in the future to include user-specific settings like light/dark mode or custom themes.
* Changed the Pinia stores (in all stores) from **export let** to **export const** to follow best practices (keep its definition constant and not be reassigned)

## v0.7.4.7
January 11, 2024
* Changed the layout/positioning of the on screen display (OSD) in FullPage
* Changed Now Playing info on the FullPage mobile
  * Created new methods in the ShowStore to set name, url, episodeName, episodeUrl
* Created 3 new methods in the VideoPlayerStore:
  * setNowPlayingInfoVideoFile
  * setNowPlayingInfoShow
  * clearNowPlaying

## v0.7.4.6
January 10, 2024
* Added placeholder message to Team Edit select Team Leader when no user is available as team leader.
  * If there are no eligible team leaders yet someone is already a team leader, now when you save the edit page they will be removed as a team leader. This needs some future logic about what happens if a Team leader's creator account becomes frozen or suspended.
* FullPage Desktop mode on screens wider than 800px:
  * Make chat background transparent in FullPage Desktop mode on screens wider than 800px.
  * Move close chat button to bottom (same as the other Ott Panels)
* Fix the CSS styling for the chat on all displays.
* Fix the problem with the chat input not working properly on mobile.

## v0.7.4.5
January 10, 2024
* Added black circle background to the question mark tool tips (PopperJs) on the creator dashboard page for Teams and Shows
* Fix the logout function on navMenu and responsiveNavMenu + force a page reload after logout.
* Fixed the chat message timestamps. New messages now display the correct "just now" time stamp.
* Fixed the chat messages not properly scrolling on Top Right mode.
* Created new utility class (css) to .hide-scrollbar
* Fixed the layout of the OttButtons in FullPage Mobile/Tablet
* Changed the OttPanels in FullPage Mobile/Tablet to only take up the bottom half of the screen.
* Added the words "scroll the menu" back to the bottom of the ResponsiveNavMenu, and it fades out when the user starts to scroll.

## v0.7.4.4
January 10, 2024
* Updated the Laravel Forge Repository Badge to be displayed in the README.md on github (notTVglobal/nottvbeta).
* Add a notification to Create Show page, if a user doesn't have a team and lands on this page a modal pops up (using NotificationStore) and asks them to make a team first.
* Created DialogNotification in AppLayout
  * Open the Dialog with the function: notificationStore.openDialogNotification
    * set notificationStore.title
    * set notificationStore.body
  * This notification closes when you tap or click outside the modal or push ESC. The notificationStore is cleared when the modal closes.

## v0.7.4.3
January 10, 2024
* Add feature, Team Creator can now select self or a manager to be the Team Leader.
  * Team Leader and Team Creator can make Team Members into Team Managers or remove managers.
  * Team Managers, Team Leader and Team Creator can:
    * add new Team Members.
    * create new shows.
    * edit shows.
  * Only the Team Leader or Team Creator can edit a team.

## v0.7.4.2
January 9, 2024
* CSS styling changes to the Welcome page
* Added a footer to the Welcome page
* Updated the text copy on the Welcome page

## v0.7.4.1
January 8, 2024
* feature/stream-ui-ux-improvement.
  * Stream page makes video full page, no longer changes the channel.
  * Click the video in TopRight to go to the Stream page and FullPage mode (on mobile/tablet)
  * Click the video in TopRight to toggle the controls (on desktop)

## v0.7.4.0
January 8, 2024
* Updates to node packages:
  * @videojs/http-streaming upgraded from 2.16.2 to 3.9.1
  * @vue/compiler-sfc upgraded from 3.4.5 to 3.4.6
  * @vueuse/components upgraded from 8.9.4 to 10.7.1
  * @vueuse/core upgraded from 8.9.4 to 10.7.1
  * @vueuse/shared upgraded from 8.9.4 to 10.7.1
  * daisyui upgraded from 3.9.4 to 4.5.0
  * file-type upgraded from 17.1.6 to 19.0.0
  * node-polyfill-webpack-plugin upgraded from 2.0.1 to 3.0.0
  * !!! pusher-js NOT upgraded from 7.6.0... This was a breaking change to 8.4.0-rc2
  * vue upgraded from 3.4.5 to 3.4.6
* Upgraded Node from 18.17.1 to 21.5.0

## v0.7.3.16
January 8, 2024
* Setup a staging server
* Fixed policies for creating, editing, managing shows/teams/episodes where a user who was not a member of a team could access a show or episode to edit.
* Fixed date-fns import on some pages that were importing date-fns-tz with errors (need to import the main library: date-fns)
* Updated axios to the latest version, but a vulnerability is still being listed (inertiajs and inertia-vue3 are referencing the vulnerable version of axios)
* The Show/Edit bug is fixed, where we ran into the error "J is not defined" when Vue was compiled for production (the bug was only on the production server)

## v0.7.3.15
November 8, 2023
* Add signup for newsletter on home page.
* The show/episode create page still isn't working in production on the live server.

## v0.7.3.14b
November 5, 2023
* Fix Show Episode Create page, blank page error.

## v0.7.3.14
October 22, 2023
* Add ULIDs to Videos and Show Episodes
* Add Episode ID to the bottom of the Episode Manage Page, only visible to users who can edit the episode.
* Add a modal to confirm Episode Publish.
* Add All Episodes page for Admin Only. This allows the admin to search through all episodes to help assist creators and teams.
* Admin can now un-publish an episode. This will affect promotional links and should only be done if absolutely necessary at the team leader's request with the appropriate team approvals.
* Admin can now change the status of a published episode to Frozen or Restricted, as per community requirements.
* Fix Release Date must be in the past if it's changed by an Admin after the episode is published.
* Created a userStore.btnRedirect() function to generate a userStore.prevUrl variable. This allows 2 new buttons to be used for page navigations, CancelButton and BackButton.
* Fix Back buttons and Cancel buttons to use the new prevUrl variable.
* Removed News edit and delete buttons from News Index page. Edit and Delete buttons are now only available from in the Newsroom Index.
* Change the Newsroom and News Index header styling.
* Change the News Post layout
* Make the News Edit text area larger
* Added additional text editor functions, such as horizontal line, code block, block quote, undo and redo. The bullet point and numbered list functions are NOT working right now.

## v0.7.3.13
October 14, 2023
* Fix video player Back button. Added a PrevUrl to the userStore.
* Added Stream Page to navigation. Stream loads a single channel. This is esp. for non-premium users.
* Fixed the videojs controlbar showing up occasionally. The controlbar is now hidden in the app.css.

## v0.7.3.12
October 12, 2023
* Fix video uploader error

## v0.7.3.11
October 6, 2023
* Re-designed Schedule Page to only show 1 channel and include show posters (a placeholder for now). Show's can display a different size poster depending on how long their time slot is. Longer time slots have larger posters.
* Updated the Now Playing Info ott panel with actual info if a Show Episode is played
* Fixed the Show FirstPlay button.
* Cleaned up the Show Episode Manage page. Made the Show Rundown and extra buttons hidden (these are features to develop later).
* Fix the bg-opacity on the Welcome page.
* Made sub-category hidden on Show, Show Episode and Movie pages

## v0.7.3.10
October 5, 2023
* New design for Show Episode Mange page.
* Fixed notifications, problem deleting when only one notification
* Added notifications if a user is added to a team or removed from a team
* If a user is removed from a team they are removed as a team manager
* If an episode is published only an admin can change the releaseDateTime
* If an episode is scheduled for release it says how long until the release on the Episode Manage page.
* Added an example Episode Rundown on the episode manage page.
* Fixed some dark: css styling
* Reverted the Paginator back to the previous style, it displays too many page links for smaller screens, this will need to be fixed later.
* Some other styling fixes

## v0.7.3.9
October 3, 2023
* Fix team members number wrong on Team Manage page
* Fix notifications not showing up
* Fix border bottom not showing up on Responsive Navigation Menu
* Added Delete All feature to the notifications when there is more than 1 notification
* Added Team Managers with the ability to create and edit shows and show episodes.
* Added the ability for Team Leader and Team Managers to delete episodes
* Added Team Notifications, when an episode is created or deleted all team members get notified.
* Updated the paginator to only display 3 page buttons ( it doesn't look right when there is less than 3 pages)
* Fixed the css on the dashboard
* Fixed the way teams and show appear on the dashboard, now Team Members will see their teams and shows.
* Added a new validation for Show Episodes, the video URL must end in .mp4

## v0.7.3.8
October 1, 2023
* Added Subscription Plans to the database
* Add new function for Admin to add an existing Premium Subscriber's subscription to the database from Stripe.
* Admin will now be able to see a user's subscription plan, renewal date and subscription status from the user's profile page.

## v0.7.3.7
September 30, 2023
* Added a videoPlayer progress bar on the Stream page, you can hover over it to display the time you will seek to. Added a rough in to display sprite images of the video.
* Fixed video player controls. Click the video in Stream mode to play/pause.
* Fixed the user timezone/timestamp on notifications
* Added more testing credits (thank you ScrapperAPI), EmbedCode to Url is testable again.
* Added a new Problem-Solution description of notTV to the Welcome page (generated by ChatGPT after multiple iterations and voice prompts with Travis)
* Added a rough outline for a Membership Agreement in the .md files.
* Added a first draft Investment Proposal in the .md files.

## v0.7.3.6b
September 27, 2023
* Brought back Show Episode Manage page.

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
