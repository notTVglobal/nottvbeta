# not.tv Changelog

Last Update: March 17, 2024\
Travis Michael Cross <a href="mailto:travis@not.tv">travis@not.tv</a>

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
