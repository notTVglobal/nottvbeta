# not.tv Changelog


Last Update: January 10, 2024\
Travis Michael Cross <a href="mailto:travis@not.tv">travis@not.tv</a>

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
