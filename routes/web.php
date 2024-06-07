<?php

use App\Http\Controllers\Api\ChannelApiController;
use App\Http\Controllers\AppSettingController;
use App\Http\Controllers\ChangelogController;
use App\Http\Controllers\ChannelController;

use App\Http\Controllers\ChannelExternalSourceController;
use App\Http\Controllers\ChannelPlaylistController;
use App\Http\Controllers\GoLiveController;
use App\Http\Controllers\InviteCodeController;
use App\Http\Controllers\MistServerController;
use App\Http\Controllers\MistStreamController;
use App\Http\Controllers\MistStreamPushDestinationController;
use App\Http\Controllers\NewsFederalElectoralDistrictController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\NewsPersonMessageController;
use App\Http\Controllers\NewsPersonRoleController;
use App\Http\Controllers\NewsPressReleaseController;
use App\Http\Controllers\NewsRssArchiveController;
use App\Http\Controllers\NewsRssFeedItemTempController;
use App\Http\Controllers\NewsTipController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\RecordingController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SubscriptionPlanController;
use App\Http\Controllers\TeamManagersController;
use App\Http\Controllers\TestMessageController;
use App\Http\Controllers\WelcomeController;
use App\Http\Middleware\HandleInertiaRequests;
use App\Mail\VerifyMail;
use App\Models\Creator;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\CreatorsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ShowsController;
use App\Http\Controllers\ShowEpisodesPosterController;
use App\Http\Controllers\ShowsPosterController;
use App\Http\Controllers\TeamsLogoController;
use App\Http\Controllers\ShowEpisodeController;
use App\Http\Controllers\MovieUploadController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\TeamsController;
use App\Http\Controllers\TeamMembersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SchedulesController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\NewsPersonController;
use App\Http\Controllers\NewsStoryController;
use App\Http\Controllers\NewsroomController;
use App\Http\Controllers\NewsRssFeedController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\VideoUploadController;
use App\Http\Controllers\WhitepaperController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\StripeController;
use App\Models\User;
use App\Models\Show;
use App\Models\ShowEpisode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Checkout;

//header('Access-Control-Allow-Origin: https://checkout.stripe.com');
//header('Access-Control-Allow-Origin: https://billing.stripe.com');
//header('Access-Control-Allow-Origin: https://ia800300.us.archive.org');
//header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
//header("Access-Control-Allow-Headers: *");

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('test', function () {
  return Inertia::render('Test');
})->name('test');

Route::get('time', function () {
  return Inertia::render('Time');
})->name('time');

Route::get('date', function () {
  return Inertia::render('Date');
})->name('date  ');

Route::get('overlays/stream-preview', function () {
  return Inertia::render('Stream/StreamPreview');
})->name('stream.preview');

Route::get('/', [WelcomeController::class, 'index'])->name('home')->middleware('refresh.session');

Route::get('/api/ping', function () {
  return response()->json(['message' => 'Session refreshed']);
});

Route::get('/send-mail', function () {
  Mail::to('test@test.com')->queue(new VerifyMail());
});

Route::get('/home', function () {
  if (Auth::check()) {
    $user = Auth::user();

    if (Session::get('creatorRegistration', false)) {
      // Retrieve the invite code from the session
      $inviteCodeUlid = Session::get('inviteCodeUlid');
      $inviteCode = Session::get('inviteCode');

      // Clear the session variables after using them
      Session::forget(['creatorRegistration', 'inviteCodeUlid', 'inviteCode']);

      // Check if the request is already on the registration page
      if (url()->previous() == route('creator.register.show', ['inviteCode' => $inviteCodeUlid])) {
        // If already on the registration page, just return back with the session data
        return back()->with([
            'inviteCodeUlid' => $inviteCodeUlid,
//            'inviteCode' => $inviteCode
        ]);
      }

      // Otherwise, redirect to the creator registration page with the invite code
      return redirect()->route('creator.register.show', ['inviteCode' => $inviteCodeUlid]);
    }

    if ($user->isCreator()) {
      return redirect()->route('dashboard');
    }

    return redirect()->route('stream');
  }

  return redirect('/');
});

Route::get('/video/{video}', [VideoController::class, 'show'])->name('video.show');

Route::get('/public/register', function () {
  return Inertia::render('Register');
})->name('public.register');

Route::get('/public/login', function () {
  return Inertia::render('Login');
})->name('public.login');

Route::get('/newsletterSignup', function () {
  return Inertia::render('NewsletterSignup');
})->name('public.newsletterSignup');

Route::get('/contact', function () {
  return Inertia::render('Contact');
})->name('public.contact');

Route::post('/contact', [WelcomeController::class, 'contactFormSubmission'])->name('public.contact.submit')->middleware('throttle:3,1');

Route::get('/public/forgot-password', function () {
  return Inertia::render('Public/ForgotPassword');
})->name('public.forgotPassword');

Route::get('/email/verify', function () {
  return Inertia::render('Auth/VerifyEmail');
})->middleware('auth')->name('verification.notice');

Route::get('/public/mail/verify', function () {
  return Inertia::render('Public/EmailVerify');
})->middleware('auth')->name('public.email.verify');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
  $request->fulfill();

  // Check if the currently authenticated user is a creator
  if (Auth::user()->creator) { // Assuming isCreator() is a method that determines if the user is a creator
    return redirect('/dashboard');
  }

  return redirect('/');
})->middleware(['auth', 'signed', 'update.last_login'])->name('verification.verify');

Route::post('/email/verify', function (Request $request) {
  $request->user()->sendEmailVerificationNotification();
  // Flash a status message to the session
  session()->flash('success', 'verification-link-sent');

  return Inertia::render('Auth/VerifyEmail');
})->middleware(['auth', 'update.last_login', 'throttle:6,1'])->name('custom.verification.send');

// Jetstream/Fortify came with an email verification method, but
// I can't figure out where the verification.send route is. And
// when I created this form.post to verification.send I got an
// error when running "php artisan route:cache" -> Unable to
// process route [email/notificationsent] for serialization.
// Another route has already been assigned name
// [verification.send] ~ tec21 (March 21, 2023)

Route::get('/testJob', function () {
  foreach (range(1, 100) as $i) {
    \App\Jobs\TestJob::dispatch()->onQueue('tests');
  }

  return Inertia::render('Testing');
});

Route::get('/terms', function () {
  return redirect('/terms-of-service');
})->name('terms');

Route::get('/privacy', function () {
  return redirect('/privacy-policy');
})->name('privacy');

Route::get('/privacy-policy', [\App\Http\Controllers\PrivacyPolicyController::class, 'show'])->name('policy.show');
Route::get('/terms-of-service', [\App\Http\Controllers\TermsOfServiceController::class, 'show'])->name('terms.show');

Route::get('/whitepaper', [WhitepaperController::class, 'show'])->name('whitepaper.show');

Route::get('/first-play-data', [AppSettingController::class, 'serveFirstPlayData']);

Route::get('/fallback', function () {
  return Inertia::render('Fallback', [
      'error' => session()->flash('error', 'The link provided does not work.')
  ]);
})->name('fallback.route');


// Creator Invite and Register (public, everyone can see these)
////////////////////////////////////////////////////////////////

//
//Route::get('/invite', function () {
//  return Inertia::render('Invite');
//})->can('viewCreator', 'App\Models\User')
//    ->name('invite');

Route::get('/invite', [CreatorsController::class, 'invite'])->name('invite');
Route::post('/invite', [WelcomeController::class, 'inviteFormSubmission'])->name('public.invite.submit')->middleware('throttle:3,1');
// note: the {code} is the primary key which is the ulid for the code.
Route::post('/invite/{inviteCode}/send-creator-email-invitation', [CreatorsController::class, 'sendCreatorEmailInvitation'])->name('creator.sendEmailInvitation.submit')->middleware('throttle:3,1');
Route::get('/invite/{inviteCode}', [CreatorsController::class, 'showCreatorInviteIntroduction'])->name('creator.invite.show');
Route::post('/invite/{inviteCode}/check-invite-code', [CreatorsController::class, 'checkInviteCode'])->name('creator.invite.checkInviteCode');
Route::post('/invite/{inviteCode}/check-registration-access', [CreatorsController::class, 'checkRegistrationAccess'])->name('creator.invite.checkRegistrationAccess');
Route::get('/register/{inviteCode}', [CreatorsController::class, 'showRegistrationForm'])->name('creator.register.show');
//Route::post('/register/{inviteCode}', [CreatorsController::class, 'store'])->name('creator.store');
Route::post('/register/creator/{inviteCode}', [CreatorsController::class, 'register'])->name('creator.register.submit');
Route::get('/check-email', function () {
  return Inertia::render('Creators/Register/CheckEmail');
})->name('creators.register.checkEmail');

// News (public, everyone can see these)
////////////////////////////////////////

// News is no longer a resource.
// tec21: 2024-01-20 removed the model and table.
Route::get('/news', [NewsStoryController::class, 'index'])
    ->name('news.index');

// Group news reporter related routes under 'news'
Route::prefix('/news')->group(function () {
  Route::get('/reporters', [NewsPersonController::class, 'reportersIndex'])
      ->name('news.reporters.index');
  Route::get('/reporter/{newsPerson}', [NewsPersonController::class, 'reporterShow'])
      ->name('news.reporter.show');
});


Route::get('/news-stories', [NewsStoryController::class, 'fetchNewsStories'])
    ->name('news-stories');

// Redirect GET requests for 'news/story' to 'news'
Route::get('news/story', function () {
  return redirect('/news');
});
// Public news story route
Route::get('news/story/{story}', [NewsStoryController::class, 'show'])
    ->name('news/story.show');

Route::prefix('news')->group(function () {
  Route::get('/city', [NewsController::class, 'indexCities'])->name('news.city.index');
  Route::get('/city/{newsCity}', [NewsController::class, 'showCity'])->name('news.city.show');
  Route::get('/categories', [NewsController::class, 'indexCategories'])->name('news.category.index');
  Route::get('/category/{newsCategory}', [NewsController::class, 'showCategory'])->name('news.category.show');
});

Route::post('/news-tip', [NewsTipController::class, 'submitNewsTip'])->name('news-tip');

Route::post('/upload-press-release', [NewsPressReleaseController::class, 'submitPressRelease'])->name('upload-press-release');

Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe']);

// Public view .. Teams/{team}/Index
Route::resource('teams', TeamsController::class);

//         Show a team
//Route::get('/teams/{team}', [TeamsController::class, 'show'])
//    ->name('teams.show');

// Public view .. Shows/{show}/Index
Route::resource('shows', ShowsController::class);

// Public view .. Display episode page
Route::get('/shows/{show}/episode/{showEpisode}', [ShowEpisodeController::class, 'show'])
    ->name('shows.showEpisodes.show')
    ->scopeBindings();

// Public view .. Schedule/Index
Route::get('/schedule', [SchedulesController::class, 'index'])
    ->name('schedule');

// Public Creator Pages
Route::get('/creator/{creator}', [CreatorsController::class, 'show'])->name('creator.show');

Route::get('/creator/{creator}/teams', [CreatorsController::class, 'fetchTeams']);
Route::get('/creator/{creator}/news-stories', [CreatorsController::class, 'fetchNewsStories']);

Route::middleware(['auth:sanctum', 'verified'])->get('/api/user', [UsersController::class, 'getUserData']);

// BEGIN ROUTES FOR
// Logged In Users
///////////

Route::middleware([
    'auth:sanctum',
    'update.last_login',
    config('jetstream.auth_session'),
    'verified.after.first.login'
])->group(function () {

  Route::post('/clear-popup-session', function () {
    Session::forget('show_homescreen_popup');
//    return response()->json(['status' => 'success']);
  })->name('clear.popup.session');

  Route::get('/externalLink', function () {
    return Inertia::render('ExternalLink');
  })->name('externalLink');


  Route::get('/stream', function () {
    return Inertia::render('Stream/Index');
  })->name('stream');

  Route::get('/changelog', [ChangelogController::class, 'show'])->name('changelog.show');


  // Channel Playlists
////////////////////
///

  Route::resource('channelPlaylists', ChannelPlaylistController::class);
  Route::get('/admin/channel-playlist/search', [ChannelPlaylistController::class, 'adminSearchChannelPlaylists'])->can('viewAdmin', 'App\Models\User');


//
//    Route::get('/payment', function (Request $request) {
//        return Inertia::render('Payment', [
//        ]);
//    })->name('payment');
//
//    Route::get('/payment2', function (Request $request) {
//        return Inertia::render('Payment2', [
//        ]);
//    })->name('payment2');
//
//    Route::get('/payment_success', function (Request $request) {
//        return Inertia::render('PaymentSuccess', [
//        ]);
//    })->name('paymentSuccess');
//
//    Route::get('/payment_cancelled', function (Request $request) {
//        return Inertia::render('PaymentCancelled', [
//        ]);
//    })->name('paymentCancelled');
//
//    Route::get('/subscribe2', function () {
//        return Inertia::render('Subscribe2', [
////            'intent' => auth()->user()->createSetupIntent(),
//        ]);
//    })->name('subscribe2');
//    Route::get('/srmessages', function () {
//        return Inertia::render('SrMessages', [
////            'intent' => auth()->user()->createSetupIntent(),
//        ]);
//    })->name('srmessages');
//    Route::get('/srreturn', function () {
//        return Inertia::render('SrReturn', [
////            'intent' => auth()->user()->createSetupIntent(),
//        ]);
//    })->name('srreturn');


// Creator Onboarding (private)
///////////////////////////////
  Route::get('/onboarding/{step}', [CreatorsController::class, 'showOnboardingStep'])->name('onboarding.show');


// Dashboard
///////////
  Route::get('/dashboard', [DashboardController::class, 'index'])
      ->can('viewDashboard', 'App\Models\Creator')
      // tec21: it doesn't like the ->middleware option.
      // The ->can option works well.
      //
//        ->middleware('can:viewDashboard,creator')
      ->name('dashboard');


// VIP
////////
  Route::patch('/userAddToVip', [\App\Http\Controllers\UsersController::class, 'vipAdd'])
//        ->middleware('auth')->isAdmin
      ->name('user.vip.add');

  Route::patch('/userRemoveFromVip', [\App\Http\Controllers\UsersController::class, 'vipRemove'])
//        ->middleware('auth')->isAdmin
      ->name('user.vip.remove');

// Channels
///////////
  // List all channels
  Route::get('/channels', function () {
    return Inertia::render('Channels');
  })->can('viewVip', 'App\Models\User')
      ->name('channels');

  // Admin manage the channels
  Route::get('/admin/channels', [AdminController::class, 'adminChannelsPage'])
      ->can('viewAdmin', 'App\Models\User')
      ->name('admin.channels');

  Route::get('/api/channels_list', [ChannelApiController::class, 'index']);
  Route::get('/admin/channels/search/{type}', [ChannelController::class, 'search'])->can('viewAdmin', 'App\Models\User');
  Route::post('/admin/channels/add', [ChannelController::class, 'store'])->can('viewAdmin', 'App\Models\User');
  Route::post('/admin/channels/{channel}', [ChannelController::class, 'update'])->can('viewAdmin', 'App\Models\User');
  Route::post('/admin/channels/{channel}/{type}/update', [ChannelController::class, 'updateType'])->can('viewAdmin', 'App\Models\User');
  Route::post('/admin/channels/{channel}/setPlaybackPriorityType', [ChannelController::class, 'setPlaybackPriorityType'])->can('viewAdmin', 'App\Models\User');
  Route::post('/admin/channels/{channel}/setMistStream', [ChannelController::class, 'setMistStream'])->can('viewAdmin', 'App\Models\User');
  Route::post('/admin/channels/{channel}/setChannelPlaylist', [ChannelController::class, 'setChannelPlaylist'])->can('viewAdmin', 'App\Models\User');
  Route::post('/admin/channels/{channel}/setExternalSource', [ChannelController::class, 'setExternalSource'])->can('viewAdmin', 'App\Models\User');
  Route::post('/admin/channels/{channel}/toggleChannelActive', [ChannelController::class, 'toggleChannelActive'])->can('viewAdmin', 'App\Models\User');


  // Schedule
///////////

  // Admin manage the schedule
  Route::get('/admin/schedule', [AdminController::class, 'adminSchedulePage'])
      ->can('viewAdmin', 'App\Models\User')
      ->name('admin.schedule');


// Shop
///////////
//    Route::resource('shop', ShopController::class);
  // List all products

  Route::get('/shop', [ShopController::class, 'index'])
      ->name('shop');

  Route::get('/shop/checkout', [ShopController::class, 'checkout'])
      ->name('checkout');

  Route::post('/shop/summary', [ShopController::class, 'summary'])
      ->name('shop.summary');

  Route::redirect('/shop/summary', '/shop');

  Route::resource('product', ProductController::class);

  Route::get('/api/products', [\App\Http\Controllers\Api\ProductController::class, 'index'])
      ->name('api.products');

  Route::get('/shop/products', [ProductController::class, 'index'])
      ->name('products');

  Route::get('/shop/product/{product}', [ProductController::class, 'show'])
      ->name('shop.product.show');

  Route::post('/shop/purchase', [StripeController::class, 'purchase']);

  // tec21: We may have to re-evaluate this...
  // can the setup intent happen on the payment page?
  // the user still has a choice here about doing a
  // subscription or a donation (one-time payment).
//  Route::get('/contribute', function () {
//    return Inertia::render('Shop/Contribute', [
//        'intent' => auth()->user()->createSetupIntent(),
//    ]);
//  })->name('contribute');

  Route::get('/contribute', [ShopController::class, 'contributeIndex'])->name('contribute.index');

  Route::get('/shop/get-favourite-options', [ShopController::class, 'getFavouriteOptions']);
  //////////////////////////////////////////////////

  Route::get('/contribute/one-time', [StripeController::class, 'OneTimeContribution'])
      ->name('contribute.one-time');

  Route::post('/shop/payment-intent', [StripeController::class, 'createPaymentIntent'])
      ->name('shop.payment-intent');

  Route::post('/upgrade', [StripeController::class, 'createCheckoutSession'])
      ->name('createCheckoutSession');

  Route::get('/contribute/subscription', [StripeController::class, 'subscription'])
      ->name('contribute.subscription');

  Route::post('/contribute/subscription', [StripeController::class, 'setupNewSubscription'])
      ->name('contribute.subscription.post');

  Route::get('/contribute/subscription_success', [StripeController::class, 'subscriptionSuccess'])
      ->name('subscriptionSuccess');

  Route::get('/contribute/subscription_success/stripe_return_url', [StripeController::class, 'subscriptionSuccessReturnUrl'])
  ->name('contribute.subscription_success.return_url');

  Route::get('/contribute/donation_success', [StripeController::class, 'donationSuccess'])
      ->name('donationSuccess');

  Route::post('/payment/setup', [StripeController::class, 'initiateSetup']);
  Route::post('/payment/initiate', [StripeController::class, 'initiatePayment']);
  Route::post('/payment/complete', [StripeController::class, 'completePayment']);
  Route::post('/payment/failure', [StripeController::class, 'failPayment']);

  Route::post('/admin/getUserSubscriptionsFromStripe', [StripeController::class, 'getUserSubscriptionsFromStripe'])
      ->name('getUserSubscriptionsFromStripe');

  // tec21: this route isn't used yet. This uses Stripe Checkout.
//    Route::get('shop/product-checkout', function (Request $request) {
//        return Checkout::guest()
//            ->withPromotionCode('promo-code')
//            ->create('price_tshirt', [
//                'success_url' => route('your-success-route'),
//                'cancel_url' => route('your-cancel-route'),
//            ]);
//    });

  Route::get('/billing', function () {
    return Inertia::render('Shop/Billing', [

    ]);
  })->name('billing');

  Route::get('/billing-portal', function (Request $request) {
    return $request->user()->redirectToBillingPortal(route('stream'));
  })->name('billingPortal');

  Route::get('/billing-portal-access', [StripeController::class, 'getBillingPortalAccessUrl']);


  // Newsroom
///////////

  // Insert Newsroom Routes here.
  Route::get('/newsroom', [NewsroomController::class, 'index'])
      ->middleware('can:viewAny,App\Models\NewsPerson')
      ->name('newsroom');

  Route::patch('/newsroom/publish/{newsStory}', [NewsroomController::class, 'publish'])
      ->name('newsroom.publish');

  Route::post('/newsStory/save', [NewsStoryController::class, 'save'])
      ->name('newsStory.save');

  Route::post('/newsStory/cache', [NewsStoryController::class, 'cacheContent'])
      ->name('newsStory.cache');


  // NewsStory
  ////////////
  // Separate route for the NewsStory 'index' method
  // tec21: the reason for this is because the News Team (NewsPersons)
  // creates, edits and reviews the stories before publishing. The public route
  // is the /news route.
  Route::get('newsroom/stories', [NewsStoryController::class, 'index'])->name('news.story.index');

  // Custom resource routes for 'news/story', excluding the 'index' method
  Route::resource('newsStory', NewsStoryController::class)->except(['index', 'show']);
  Route::patch('newsStoryChangeNewsStoryStatus', [NewsStoryController::class, 'changeStatus'])->name('news.story.changeStatus');

  Route::get('/api/news/cities', [NewsStoryController::class, 'fetchNewsCities']);
  Route::get('/api/news/categories', [NewsStoryController::class, 'fetchCategories']);

// News Districts
/////////////////

  Route::get('/news-districts/', [NewsFederalElectoralDistrictController::class, 'index'])
      ->name('newsDistricts.index');


// NewsPerson
/////////////

  // Insert NewsPerson Routes here.
  // Standard resource route for newsPerson
  Route::resource('newsroom/newsPerson', NewsPersonController::class);

  Route::get('/api/news/persons', [NewsPersonController::class, 'fetchNewsPersons']);

  Route::get('/api/news/news-person-fetch-settings/{newsPerson}', [NewsPersonController::class, 'fetchNewsPersonSettings']);

// News Person Messages
/////////////

  Route::resource('news-person-messages', NewsPersonMessageController::class);
  Route::post('news-person-messages/{newsPersonMessage}', [NewsPersonMessageController::class, 'destroy']);

//  Route::post('/news-person-messages/store', [NewsPersonMessageController::class, 'store']);

//  Route::post('/news-person-messages/store', [NewsPersonMessageController::class, 'show']);

  Route::get('/news-person-messages-fetch', [NewsPersonMessageController::class, 'fetchMessages']);

  Route::get('/news-person-messages-count', [NewsPersonMessageController::class, 'count']);

//  Route::post('news-person-messages/{id}/restore', [NewsPersonMessageController::class, 'restore'])
//      ->name('news-person-messages.restore');
//
//  Route::delete('news-person-messages/{id}/force-delete', [NewsPersonMessageController::class, 'forceDelete'])
//      ->name('news-person-messages.forceDelete');

  Route::post('/news-person-messages-delete-all', [NewsPersonMessageController::class, 'deleteAll'])
      ->name('news-person-messages-deleteAll');


// News Locations and APIs
//////////////////////////

// News People Roles
////////////////////

  Route::get('api/news-people-roles', [NewsPersonRoleController::class, 'index'])->name('newsPersonRole.index');
  Route::get('/newsroom/newsPerson/{id}/roles', [NewsPersonController::class, 'fetchRoles']);
  Route::post('/newsroom/newsPerson/updateRoles', [NewsPersonController::class, 'updateRoles'])
      ->can('viewAdmin', 'App\Models\User');


// NewsRssFeeds
///////////////

  // Route for NewsRssFeeds
  Route::resource('/newsRssFeeds', NewsRssFeedController::class);

  // Route for NewsRssFeeds
//  Route::get('/newsRssFeeds/listFeeds', 'App\Http\Controllers\NewsRssFeedController@listFeeds')->name('newsRssFeeds.listFeeds');

  // Resource route for temporary feed items
  Route::resource('/newsRssFeedItemsTemp', NewsRssFeedItemTempController::class);

  Route::patch('/newsRssFeedItemsTemp/{newsRssFeedItemTemp}/save', [NewsRssFeedItemTempController::class, 'saveItem'])
      ->name('newsRssFeedItemsTemp.save');

  // Resource route for archived feed items
  Route::resource('/newsRssArchive', NewsRssArchiveController::class);


// Subscriptions
////////////////

  // Index page
  Route::get('/admin/subscriptions', [SubscriptionPlanController::class, 'index'])->name('subscription-plans.index');

  // Show page
  Route::get('/admin/subscription/{subscriptionPlan}', [SubscriptionPlanController::class, 'show'])->name('subscription-plans.show');

  // Create page
  Route::get('/admin/subscriptions/create', [SubscriptionPlanController::class, 'create'])->name('subscription-plans.create');

  // Store method (for creating)
  Route::post('/admin/subscription', [SubscriptionPlanController::class, 'store'])->name('subscription-plans.store');

  // Edit page
  Route::get('/admin/subscription/{subscriptionPlan}/edit', [SubscriptionPlanController::class, 'edit'])->name('subscription-plans.edit');

  // Update method (for updating)
  Route::patch('/admin/subscription/{subscriptionPlan}', [SubscriptionPlanController::class, 'update'])->name('subscription-plans.update');

  // Delete method (for deleting)
  Route::delete('/admin/subscription/{subscriptionPlan}', [SubscriptionPlanController::class, 'destroy'])->name('subscription-plans.destroy');


// Library
///////////
  Route::get('/library', function () {
    return Inertia::render('Library');
  })->can('viewVip', 'App\Models\User')
      ->name('library');


// For Testing
///////////


  // temp page to test Stores
  Route::get('/quiz', function () {
    return Inertia::render('QuizHome');
  })->can('viewAdmin', 'App\Models\User')
      ->name('quiz');

// Admin Pages
///////////

  //// temp phpinfo page for testing and debugging on a new server.
//    Route::get('/admin/phpmyinfo', function () {
//        phpinfo();
//    })->name('admin.phpmyinfo');

  //// A one-time use function for
  ///  getting video urls from embed
  ///  codes for all episodes.
  ///
  ///

  Route::post('/admin/getVideosFromEmbedCodes', [AdminController::class, 'getVideosFromEmbedCodes'])
      ->can('viewAdmin', 'App\Models\User')
      ->name('getVideosFromEmbedCodes');

  //// temp page to test Stores
  Route::get('/quiz', function () {
    return Inertia::render('QuizHome');
  })->can('viewAdmin', 'App\Models\User')
      ->name('quiz');

  //// ADMIN: SETTINGS
  Route::get('/admin/settings', [AdminController::class, 'settings'])
      ->can('viewAdmin', 'App\Models\User')
      ->name('admin.settings');

  //// ADMIN: SETTINGS - SAVE
  Route::patch('/admin/settings', [AdminController::class, 'saveSettings'])
      ->can('viewAdmin', 'App\Models\User')
      ->name('admin.saveSettings');

  //// ADMIN: FIRST PLAY SETTINGS

  Route::post('/admin/fetch-first-play-settings', [AdminController::class, 'fetchFirstPlaySettings'])
      ->can('viewAdmin', 'App\Models\User')
      ->name('admin.fetchFirstPlaySettings');

  Route::patch('/admin/update-first-play-settings', [AdminController::class, 'updateFirstPlaySettings'])
      ->can('viewAdmin', 'App\Models\User')
      ->name('admin.updateFirstPlaySettings');

  Route::post('/admin/clear-first-play-data-cache',
      [AdminController::class, 'clearFirstPlayDataCache'])
      ->can('viewAdmin', 'App\Models\User')
      ->name('admin.clearFirstPlayDataCache');

  Route::post('/admin/fetch-active-streams', [AdminController::class, 'fetchActiveStreams'])
      ->can('viewAdmin', 'App\Models\User')
      ->name('admin.fetchActiveStreams');

  //// ADMIN: BAN USERS
  Route::post('/admin/ban-user/{userId}', [AdminController::class, 'banUser'])
  ->can('viewAdmin', 'App\Models\User');
  Route::post('/admin/unban-user/{userId}', [AdminController::class, 'unbanUser'])
  ->can('viewAdmin', 'App\Models\User');
  Route::get('/admin/banned-users', [AdminController::class, 'bannedUsers'])
      ->can('viewAdmin', 'App\Models\User');

  //// ADMIN: SECURE NOTES
  Route::get('/admin/secure-notes',
      [AdminController::class, 'fetchSecureNotes'])
      ->can('viewAdmin', 'App\Models\User');

  Route::put('/admin/secure-notes',
      [AdminController::class, 'putSecureNotes'])
      ->can('viewAdmin', 'App\Models\User');


  //// ADMIN: MOVIES - INDEX
  Route::get('/admin/movies', [AdminController::class, 'moviesIndex'])
      ->can('viewAdmin', 'App\Models\User')
      ->name('admin.movies');

  //// ADMIN: SHOWS - INDEX
  Route::get('/admin/shows', [AdminController::class, 'showsIndex'])
      ->can('viewAdmin', 'App\Models\User')
      ->name('admin.shows');

  //// ADMIN: EPISODES - INDEX
  Route::get('/admin/episodes', [AdminController::class, 'episodesIndex'])
      ->can('viewAdmin', 'App\Models\User')
      ->name('admin.episodes');

  //// ADMIN: TEAMS - INDEX
  Route::get('/admin/teams', [AdminController::class, 'teamsIndex'])
      ->can('viewAdmin', 'App\Models\User')
      ->name('admin.teams');

  //// ADMIN: INVITE CODES - INDEX
  Route::get('/admin/invite_codes', [AdminController::class, 'inviteCodes'])
      ->can('viewAdmin', 'App\Models\User')
      ->name('admin.inviteCodes');

  //// ADMIN: INVITE CODES - SAVE
  Route::post('/admin/invite_codes', [AdminController::class, 'saveInviteCodes'])
      ->can('viewAdmin', 'App\Models\User')
      ->name('admin.saveInviteCodes');

  //// INVITE CODES - MY CODES
  Route::get('/invite_codes/my-codes', [InviteCodeController::class, 'viewMyCodes'])
      ->name('inviteCodes.myCodes');


  //// ADMIN: NEW INVITE CODE ROUTES FOR INVITECODECONTROLLER

  // ADMIN: Listing invite codes
  Route::get('/invite_codes', [InviteCodeController::class, 'index'])
      ->can('viewAdmin', 'App\Models\User')
      ->name('inviteCodes');

  // Search invite codes
//  Route::post('/invite_codes', [InviteCodeController::class, 'index'])->name('inviteCodes')
//      ->can('viewAdmin', 'App\Models\User');
  // ADMIN:
  Route::get('/generate-invite-code', [InviteCodeController::class, 'generateUniqueInviteCodeJson'])
      ->name('generate.invite.code')
      ->can('viewAdmin', 'App\Models\User');

  // ADMIN: Showing the form to create a new code
  Route::get('/invite_codes/create', [InviteCodeController::class, 'create'])->name('inviteCodes.create')
      ->can('viewAdmin', 'App\Models\User');

  // ADMIN: Storing the new code
  Route::post('/invite_codes', [InviteCodeController::class, 'store'])->name('inviteCodes.store')
      ->can('viewAdmin', 'App\Models\User');

  // ADMIN: Storing the new code
  Route::post('/invite_codes/quick-add', [InviteCodeController::class, 'inviteCodeQuickAdd'])->name('inviteCodes.quickAdd')
      ->can('viewAdmin', 'App\Models\User');

  Route::get('/invite_codes/{inviteCode}/edit', [InviteCodeController::class, 'edit'])
      ->name('inviteCodes.edit')
      ->can('viewAdmin', 'App\Models\User');

  Route::put('/invite_codes/{inviteCode}', [InviteCodeController::class, 'update'])
      ->name('inviteCodes.update')
      ->can('viewAdmin', 'App\Models\User');

  //// ADMIN: INVITE CODES - EXPORT
  Route::get('/invite_codes/export', [InviteCodeController::class, 'exportInviteCodes'])
      ->can('viewAdmin', 'App\Models\User')
      ->name('inviteCodes.export');


  // ADMIN: Delete the code
  Route::delete('/invite_codes/{inviteCode}', [InviteCodeController::class, 'destroy'])
      ->name('inviteCodes.destroy')
      ->can('viewAdmin', 'App\Models\User');

  Route::get('/invite_codes/report', [InviteCodeController::class, 'report'])->name('inviteCodes.report')
      ->can('viewAdmin', 'App\Models\User');

  Route::get('/admin/users/search', [UsersController::class, 'search'])->name('admin.users.search')
      ->can('viewAdmin', 'App\Models\User');

  Route::post('/invite_codes/claim/{codeId}', [InviteCodeController::class, 'claimCode'])->name('invite_codes.claim')
      ->can('viewAdmin', 'App\Models\User');

  Route::post('/invite_codes/claim_all', [InviteCodeController::class, 'claimAllCodes'])->name('invite_codes.claim_all')
      ->can('viewAdmin', 'App\Models\User');


  // Get Invite Code Settings
  Route::get('/invite-code-settings', [AppSettingController::class, 'getInviteCodeSettings']);

  // Get Invite Code Settings
  Route::patch('/invite-code-settings', [AppSettingController::class, 'patchInviteCodeSettings']);


  //// ADMIN: DELETE USER
  Route::post('/admin/user/delete', [AdminController::class, 'deleteUser'])
      ->can('delete', 'App\Models\User')
      ->name('admin.deleteUser');


  //// ADMIN: UPLOAD DATA FOR NEWS TABLES (CITIES, PROVINCES, DISTRICTS)
  Route::post('/admin/upload-news-data', [AdminController::class, 'uploadNewsData'])->name('admin.uploadNewsData');


  //// CALCULATIONS
  Route::get('/calculations', function () {
    return Inertia::render('Calculations');
  })->can('viewAdmin', 'App\Models\User')
      ->name('calculations');

  //// MIST SERVER API
  Route::get('/video', function () {
    return Inertia::render('Admin/MistServerApi');
  })->can('viewAdmin', 'App\Models\User')
      ->name('video');

  Route::get('/admin/mistServerApi', function () {
    return Inertia::render('Admin/MistServerApi');
  })->can('viewAdmin', 'App\Models\User')
      ->name('mistServerApi');

  //// IMAGE GALLERY + UPLOAD
  Route::get('/admin/images', [ImageController::class, 'index'])
      ->can('viewAdmin', 'App\Models\User')
      ->name('admin.image.index');

  //// GET SERVER TIME
  Route::get('/admin/server-time', [AdminController::class, 'getServerTime']);

  // Teams - Part 1 (part 2 is in the Creator Resources below)
  ///////////

  // Public view .. Teams/{team}/Index
//  Route::resource('teams', TeamsController::class);
////         Show a team
//  Route::get('/teams/{team}', [TeamsController::class, 'show'])
//      ->middleware('can:view,team')
//      ->name('teams.show');


  /////////////////  CREATOR RESOURCES ////////////////
  /// //////////////////////////////////////////// ///

// Creator Resources
  // Begin middleware authorization
  // allow creators access to the
  // following pages.
///////////
  Route::middleware([
      'can:viewAny, App\Models\Creator'
  ])->group(function () {

    Route::get('/user/creator/get-settings/{user}', [CreatorsController::class, 'fetchCreatorSettings'])
        ->name('user.creator.settings');

    Route::patch('/user/creator/update-settings/{user}', [CreatorsController::class, 'updateCreatorSettings'])
        ->name('user.creator.settings.update');


    // Teams - Part 2 (part 1 is outside the Creator Resources above)
    ///////////

    // List all teams
//    Route::get('/teams', [TeamsController::class, 'index'])
//        //        ->middleware('can:viewAny,App\Models\User')
//        ->name('teams.index');

    // Create a team
    Route::get('/teams/create', [TeamsController::class, 'create'])
        ->middleware('can:createTeam,App\Models\Team')
        ->name('teams.create');

    // Add new team to database
    Route::post('/teams', [TeamsController::class, 'store'])
        ->middleware('can:createTeam,App\Models\Team')
        ->name('teams.store');

    // Display teams manage page
    Route::get('/teams/{team}/manage', [TeamsController::class, 'manage'])
        ->middleware('can:viewTeamManagePage,team')
        ->name('teams.manage');

    Route::post('/teams/{team}/save-public-message', [TeamsController::class, 'savePublicMessage'])
        ->middleware('can:manage,team');

    // Edit team
    Route::get('/teams/{team}/edit', [TeamsController::class, 'edit'])
        ->name('teams.edit');

    // Team transfer
    Route::post('/teams/{team}/transfer', [TeamsController::class, 'sendTransferRequest'])
        ->name('teams.sendTransferRequest');

    // Team Members
    Route::resource('teamMembers', TeamMembersController::class);

    // Fetch team members
    Route::post('/api/fetch-team-members', [TeamMembersController::class, 'fetchTeamMembers'])
        ->name('teams.fetchTeamMembers');

    // Add team member
    Route::post('/teams/addTeamMember', [TeamMembersController::class, 'attach'])
        ->name('teams.addTeamMember');

    // Remove team member
    Route::post('/teams/removeTeamMember', [TeamMembersController::class, 'detach'])
        ->name('teams.removeTeamMember');

    // Add team manager
    Route::post('/teams/addTeamManager', [TeamManagersController::class, 'attach'])
        ->name('teams.addTeamManager');

    // Remove team manager
    Route::post('/teams/removeTeamManager', [TeamManagersController::class, 'detach'])
        ->name('teams.removeTeamManager');

    // Invite team member
    Route::post('/teams/{team}/invite', [TeamMembersController::class, 'inviteMember']);


// Creators
///////////
    // Creators resource
    Route::resource('creators', CreatorsController::class);
    // Display creator page
    Route::get('/creators/{creator}', [CreatorsController::class, 'show'])
        ->name('creators.show');
    // Get list of creators
    Route::get('/api/creators', [CreatorsController::class, 'getCreators'])
        ->name('creators.getCreators');
    // Get all creators
    Route::get('/api/all-creators', [CreatorsController::class, 'getAllCreators'])
        ->name('creators.getAllCreators');
    // Mark First Time As Seen ( The Welcome Creator Message on the Dashboard )
    Route::post('/api/creator/mark-as-seen', [CreatorsController::class, 'markAsSeen'])
        ->name('creators.markAsSeen');
    // Search creators (used in TeamAddMember.vue)
    Route::get('/api/search-creators', [TeamsController::class, 'searchCreators']);

// Creator Messages
///////////////////

    Route::resource('news-person-messages', NewsPersonMessageController::class);

    Route::post('news-person-messages/{id}/restore', [NewsPersonMessageController::class, 'restore'])
        ->name('news-person-messages.restore');

    Route::delete('news-person-messages/{id}/force-delete', [NewsPersonMessageController::class, 'forceDelete'])
        ->name('news-person-messages.forceDelete');

// Training
///////////
    Route::get('/training', function () {
      return Inertia::render('Training');
    })->can('viewCreator', 'App\Models\User')
        ->name('training');

    Route::get('/training/go-live-using-zoom', function () {
      return Inertia::render('Training/GoLiveUsingZoom');
    })->can('viewCreator', 'App\Models\User')
        ->name('training.goLiveUsingZoom');

    Route::get('/training/how-to-push-to-facebook', function () {
      return Inertia::render('Training/HowToPushToFacebook');
    })->can('viewCreator', 'App\Models\User')
        ->name('training.howToPushToFacebook');

    Route::get('/training/how-to-push-to-rumble', function () {
      return Inertia::render('Training/HowToPushToRumble');
    })->can('viewCreator', 'App\Models\User')
        ->name('training.howToPushToRumble');

  });

// Shows
///////////
  // Shows resource

  // Display shows index page
//    Route::get('/shows', [ShowsController::class, 'index'])
//        ->can('viewAny', 'App\Models\Show')
//        ->name('shows');
  // Display shows manage page
  Route::get('/shows/{show}/manage', [ShowsController::class, 'manage'])
//        ->middleware('can:viewShowManagePage,show')
      ->name('shows.manage');
  // Display shows edit page
  Route::get('/shows/{show}/edit', [ShowsController::class, 'edit'])
      ->name('shows.edit');
  // Display shows create page
//    Route::get('/shows/create', [ShowsController::class, 'create'])
//        ->can('viewCreator', 'App\Models\User')
//        ->name('shows.create');
  // Update show notes
  Route::post('/shows/notes', [ShowsController::class, 'updateNotes']);

  Route::put('/api/shows/{show}/meta', [ShowsController::class, 'updateMeta'])
      ->name('shows.updateMeta');

  Route::post('/api/{show}/user-left-channel', [ShowsController::class, 'userLeftChannel']);


// Show Episodes
///////////
  // Shows resource
  Route::resource('showEpisodes', ShowEpisodeController::class);

  // Display episodes index page
  Route::get('/shows/{show}/episodes', [ShowEpisodeController::class, 'index'])
      ->name('showEpisodes');

  // Display episode create page
  Route::get('/showEpisodes/{show}/episode/create', [ShowEpisodeController::class, 'create'])
      ->name('showEpisodes.createWithSlug');

  // Display episode manage page
  Route::get('/shows/{show}/episode/{showEpisode}/manage', [ShowEpisodeController::class, 'manage'])
      ->name('showEpisode.manage')
      ->scopeBindings();

  // Display episode edit page
  Route::get('/shows/{show}/episode/{showEpisode}/edit', [ShowEpisodeController::class, 'edit'])
//        ->middleware('can:edit,show')
      ->name('shows.showEpisodes.edit')
      ->scopeBindings();
  // Delete an episode
  Route::delete('/shows/{show}/episode/{showEpisode}', [ShowEpisodeController::class, 'destroy'])
      ->name('shows.showEpisodes.destroy')
      ->scopeBindings();
  // Display episode upload page
  Route::get('/shows/{show}/episode/{showEpisode}/upload', [ShowEpisodeController::class, 'upload'])
//        ->middleware('can:edit,show')
      ->name('shows.showEpisodes.upload')
      ->scopeBindings();
  // Update episode notes
  Route::post('/shows/episode/notes', [ShowEpisodeController::class, 'updateNotes']);
  // Route to change showEpisodeStatus
  Route::post('/shows/episode/changeEpisodeStatus', [ShowsController::class, 'changeEpisodeStatus']);
//        ->middleware('can:editShowManagePage,show');
  // Update episode
//    Route::get('/shows/{show}/episode/{showEpisode}/edit', [ShowEpisodeController::class, 'update'])
//        ->middleware('can:edit,show')
//        ->name('shows.showEpisodes.update')
//        ->scopeBindings();


  // tec21: This is probably not the best way to do this
  // I'm unable to get the FilePond uploader to show the
  // uploaded image on the shows/edit page after upload.
  // this is my solution. Copy the ImageController.store
  // code to a new ShowController.uploadPoster function.
  Route::post('/showEpisodesUploadPoster', [ImageController::class, 'uploadShowEpisodePoster'])
      ->can('viewCreator', 'App\Models\User')
      ->name('showEpisodes.uploadPoster');

  Route::post('/showsUploadPoster', [ImageController::class, 'uploadShowPoster'])
      ->can('viewCreator', 'App\Models\User')
      ->name('shows.uploadPoster');

  Route::post('/teamsUploadLogo', [ImageController::class, 'uploadTeamLogo'])
      ->can('viewCreator', 'App\Models\User')
      ->name('teams.uploadLogo');

  Route::post('/moviesUploadPoster', [ImageController::class, 'uploadMoviePoster'])
      ->can('viewCreator', 'App\Models\User')
      ->name('movies.uploadPoster');

  Route::post('/api/image-upload', [ImageController::class, 'upload'])
      ->can('viewCreator', 'App\Models\User')
      ->name('image.upload');

// Movies
///////////
  // Index route
  Route::get('movies', [MovieController::class, 'index'])->name('movies.index');

  // Categories index
  Route::get('movies/categories', [MovieController::class, 'indexCategories'])->name('movies.category.index');
  Route::get('movies/{movieCategory}', [MovieController::class, 'showCategory'])->name('movies.category.show');

  // Group other resource routes under /movie
  Route::group(['prefix' => 'movie'], function () {
    Route::get('create', [MovieController::class, 'create'])->name('movie.create');
    Route::post('/', [MovieController::class, 'store'])->name('movie.store');
    Route::get('{movie}', [MovieController::class, 'show'])->name('movie.show');
    Route::get('{movie}/edit', [MovieController::class, 'edit'])->name('movie.edit');
    Route::put('{movie}', [MovieController::class, 'update'])->name('movie.update');
    Route::delete('{movie}', [MovieController::class, 'destroy'])->name('movie.destroy');
  });

// Testing
///////////
  Route::get('/testing', function () {
    return Inertia::render('Testing');
  })->can('viewAdmin', 'App\Models\User')
      ->name('testing');

// Images + Upload
///////////

  // this route needs to be refactored to display an image /image/{$id}
//    Route::get('/images', [ImageController::class, 'show'])
//        ->can('viewAdmin', 'App\Models\User')
//        ->name('image.show');

  Route::post('/upload', [ImageController::class, 'upload'])
      ->can('viewCreator', 'App\Models\User')
      ->name('image.store');

  Route::get('/upload', function () {
    return redirect('/stream');
  });

// Users
///////////
  // Users resource for admin to create/edit users
  Route::resource('users', UsersController::class);

  Route::post('/getUserStoreData', [UsersController::class, 'getUserStoreData']);
  Route::post('/users/update-timezone', [UsersController::class, 'updateTimezone'])->name('users.update.timezone');

  // List all users -- this has to be a different controller than the UsersAdminCreateEditController
  // because it uses a different resource class for the search function.
  Route::get('/users', [UsersController::class, 'index'])
      ->can('viewAny', 'App\Models\User')
      ->name('users.index');

//    // Create a user
  Route::get('/users/create', [UsersController::class, 'create'])
      ->can('create', 'App\Models\User')
      ->name('usersAdminCreateEdit.create');

//    // Add new user to the database
//    Route::post('/users', [UsersController::class, 'store'])->name('users.store');

//    // Show user
  Route::get('/users/{user}', [UsersController::class, 'show'])
      ->can('viewAny', 'App\Models\User')
      ->name('users.show');

//    // Edit user
  Route::get('/users/{user}/edit', [UsersController::class, 'edit'])
      ->can('edit', 'App\Models\User')
      ->name('users.edit');

  Route::get('/user/contact', [UsersController::class, 'getContactInformation'])->name('user.contact');

  // User settings redirect
  Route::get('/settings', [UsersController::class, 'settingsRedirect'])->name('user.settings.redirect');

  // Update user
  Route::patch('/users', [UsersController::class, 'updateContact'])->name('users.updateContact');

  // Consent to cookies
  Route::post('/users/consent-cookies', [UsersController::class, 'consentCookies'])->name('users.consentCookies');
  // Check cookie consent
  Route::get('/users/consent-cookies', [UsersController::class, 'checkCookieConsent'])->name('users.checkConsentCookies');

// Chat
///////////
///
  Route::get('/chat/channels', [ChatController::class, 'channels']);
  Route::get('/chat/channel/{channelId}/messages', [ChatController::class, 'messages']);
  Route::get('/chatTest', [TestMessageController::class, 'index']);

  Route::post('/chat/message', [ChatController::class, 'newMessage'])
      ->middleware('throttle:10,1') // Allows 10 requests per minute
      ->name('chatMessage');

//    Route::get('/chatTest', function () {
//        return Inertia::render('ChatTest');
//    })->can('viewVip', 'App\Models\User')
//        ->name('chatTest');
//

//    Route::post('/chat/channel/{channelId}/message', function() {
//        ChatMessage::forceCreate(request(['body']));
//    });

// Videos
///////////////////////////
///

//    Route::get('/videoupload', function () {
//        return Inertia::render('VideoUpload');
//    })->can('viewAdmin', 'App\Models\User')
//        ->name('videoupload');

  Route::get('/videoupload', [VideoUploadController::class, 'index'])
      ->can('viewCreator', 'App\Models\User')
      ->name('videoupload');

  Route::post('/videoupload', [VideoUploadController::class, 'upload'])
      ->can('viewCreator', 'App\Models\User')
      ->name('videoupload.upload');

  // delete video
  Route::post('/video/delete', [VideoController::class, 'destroy'])
      ->name('video.destroy');


// Go Live
/////////


  Route::get('/golive', [GoLiveController::class, 'index'])
      ->can('goLive', Creator::class)
      ->name('goLive.index');

// Get RTMP Uri
  Route::get('/fetch-rtmp-uri', [AppSettingController::class, 'getRtmpUri'])->can('viewCreator', 'App\Models\User');
// Fetch Push Destinations
  Route::post('/go-live/fetch-push-destinations', [GoLiveController::class, 'fetchPushDestinations'])->can('viewCreator', 'App\Models\User');
// List available shows
  Route::get('/go-live/shows', [GoLiveController::class, 'listAvailableShows'])->can('viewCreator', 'App\Models\User');
// List episodes for a show
  Route::get('/go-live/shows/{showId}/episodes', [GoLiveController::class, 'listEpisodesForShow'])->can('viewCreator', 'App\Models\User');
// Get stream key for an episode
  Route::post('/go-live/episodes/{showEpisode}/stream-key', [GoLiveController::class, 'getStreamKeyForEpisode'])->can('viewCreator', 'App\Models\User');
  // Get stream key for a show
  Route::post('/go-live/shows/{showId}/stream-key', [GoLiveController::class, 'getStreamKeyForShow'])->can('viewCreator', 'App\Models\User');
// Prepare live stream for an episode
  Route::post('/go-live/episodes/{episodeId}/prepare', [GoLiveController::class, 'prepareLiveStream'])->can('viewCreator', 'App\Models\User');


// MistAPI (the [original] first way we learned to connect to the Mist Server)
///////////
///

  Route::post('/mistapi', [VideoController::class, 'mistApi'])->name('mistApi');
  Route::get('/api/mistserver', [\App\Http\Controllers\MistStreamController::class, 'mistServer']);


// Mist Streams
///////////////
///

  Route::resource('mistStreams', MistStreamController::class);
  Route::get('/admin/mist-stream/search', [MistStreamController::class, 'searchMistStreams'])->can('viewAdmin', 'App\Models\User');
  Route::post('/admin/mist-stream/addOrUpdate', [MistStreamController::class, 'addOrUpdateMistStream'])->can('viewAdmin', 'App\Models\User')->name('mistStream.addOrUpdate');
  Route::post('/admin/mist-stream/remove', [MistStreamController::class, 'removeMistStream'])->can('viewAdmin', 'App\Models\User')->name('mistStream.remove');
  Route::post('/admin/mist-stream/restore-all-streams', [MistStreamController::class, 'restoreAllStreams'])->can('viewAdmin', 'App\Models\User');
  Route::post('/fetch-stream-info', [MistStreamController::class, 'fetchStreamInfo'])->can('viewCreator', 'App\Models\User');

// Mist Server
//////////////
///

  Route::get('/mist-server/uri', [MistServerController::class, 'uri']);
  Route::post('/mist-server/check-send', [MistServerController::class, 'checkSend'])->can('viewAdmin', 'App\Models\User');
  Route::post('/mist-server/check-send/playback', [MistServerController::class, 'checkSendPlayback'])->can('viewAdmin', 'App\Models\User');
  Route::post('/mist-server/check-send/recording', [MistServerController::class, 'checkSendRecording'])->can('viewAdmin', 'App\Models\User');
  Route::post('/mist-server/check-send/vod', [MistServerController::class, 'checkSendVod'])->can('viewAdmin', 'App\Models\User');
  Route::post('/mist-server/config-backup', [MistServerController::class, 'configBackup'])->can('viewAdmin', 'App\Models\User');
  Route::post('/mist-server/config-restore', [MistServerController::class, 'configRestore'])->can('viewAdmin', 'App\Models\User');


// Mist Stream Push Destinations
////////////////////////////////
///

  Route::resource('mist-stream-push-destinations', MistStreamPushDestinationController::class);
  Route::post('/mist-stream/get-push-auto-list', [MistStreamPushDestinationController::class, 'getPushAutoList']);
  Route::post('/mist-stream/get-push-list', [MistStreamPushDestinationController::class, 'getPushList']);
  Route::post('/mist-stream/push-auto-add/{mistStreamPushDestination}', [MistStreamPushDestinationController::class, 'pushAutoAdd']);
  Route::post('/mist-stream/remove-all-auto-pushes-for-stream', [MistStreamPushDestinationController::class, 'removeAllAutoPushesForStream']);
  Route::post('/mist-stream/push-auto-remove/{mistStreamPushDestination}', [MistStreamPushDestinationController::class, 'pushAutoRemove']);
  Route::post('/mist-stream/start-push', [MistStreamPushDestinationController::class, 'startPush']);
  Route::post('/mist-stream/stop-push', [MistStreamPushDestinationController::class, 'stopPush']);
  Route::post('/mist-stream/update-stream-push-status', [MistStreamPushDestinationController::class, 'updateStreamPushStatus']);
  Route::post('/mist-stream/start-recording/{show}', [RecordingController::class, 'startRecording']);
  Route::post('/mist-stream/stop-recording/{show}', [RecordingController::class, 'stopRecording']);


// External Sources
///////////////////
///

  Route::resource('externalSources', ChannelExternalSourceController::class);
  Route::get('/admin/external-source/search', [ChannelExternalSourceController::class, 'adminSearchExternalSources'])->can('viewAdmin', 'App\Models\User');


// Schedule
///////////
///


  Route::get('/api/schedule', [SchedulesController::class, 'fetchFiveDaySixHourSchedule']);
  Route::post('/api/schedule/addToSchedule', [SchedulesController::class, 'addToSchedule']);
  Route::post('/api/schedule/{id}', [SchedulesController::class, 'update']);
  Route::delete('/api/schedule/removeFromSchedule', [SchedulesController::class, 'removeFromSchedule']);
  Route::get('/api/schedule/today', [SchedulesController::class, 'fetchTodaysContent']);

  Route::post('/admin/schedule/admin-reset-cache', [SchedulesController::class, 'adminResetCache'])
      ->can('viewAdmin', 'App\Models\User');


// Recordings
/////////////

  Route::get('/api/recordings', [RecordingController::class, 'fetchRecordings']);
  Route::patch('/api/recordings/{recording}', [RecordingController::class, 'update']);

// Extra Functions
//////////////////
///
  Route::post('/clear-flash', [HandleInertiaRequests::class, 'clearFlash'])->name('flash.clear');

  Route::get('/notifications', [NotificationsController::class, 'index']);
  Route::patch('/notifications/{id}/mark-as-read', [NotificationsController::class, 'markAsRead']);
  Route::delete('/notifications/{notification}', [NotificationsController::class, 'destroy']);
  Route::delete('/notifications', [NotificationsController::class, 'destroyAll']);


// Feedback Form
////////////////
///

  Route::post('/user/feedback', [UsersController::class, 'submitFeedback'])
      ->name('user.feedback');


// API Routes simplified for MVP
////////////////////////////////
///

  Route::post('/api/messages', [ChatController::class, 'message']);
//Route::get('prometheus', 'http://mist.nottv.io:4242/nottv');

  Route::post('/api/movies/upload', [MovieUploadController::class, 'upload'])
      ->name('moviesApi.upload');

  Route::post('/api/remove-image', [ImageController::class, 'removeImage']);


// End Authenticated Routes
///////////////////////////
///
});


// Public Pages
///////////////

Route::get('/live-streaming-guide', function () {
  return Inertia::render('Training/LiveStreamGuide');
})->name('liveStreamingGuide');


// Public API Routes
////////////////////

Route::post('/api/schedule/week', [SchedulesController::class, 'preloadWeeklyContent']);
Route::post('/api/schedule/week/{formattedDateTimeUtc}', [SchedulesController::class, 'loadWeekFromDate']);
// New Routes to replace the others...
Route::get('/api/schedules/today', [SchedulesController::class, 'fetchTodaysContent']);
Route::get('/api/schedules/five-day', [SchedulesController::class, 'fetchFiveDaySixHourSchedule']);
Route::get('/api/schedules/range', [SchedulesController::class, 'fetchContentForRange']);

// Check for existing email/user account on creator registration
Route::post('/api/creators/register-check-email', [CreatorsController::class, 'registerCheckEmail'])
    ->name('creators.registerCheckEmail');

Route::get('/api/shows/{show}/check-live', [ShowsController::class, 'checkIsLive'])
    ->name('shows.checkLive');


//Route::any('/{any}', function() {
//    return view('app');
//})->where('any', '.*');

// External Redirects
/////////////////////

//Route::get('/subscribe', function () {
//    return Inertia::location('https://99fd701b.sibforms.com/serve/MUIFAAAMUBfnlUf5rgaD2zSTE_76pHldyCCXhQvz-CBNZwd9lLYST4jcuwwsudQEHOkX1isAFHV6iXvtIepJSh5RkVrZY1wUQ5yaf1j6kWyzMJ75s2FZfHOZMdO7mkE-pDv96yW4bekMX67ZevIlWsjQvdDgEXUEKqfAvvQieIM3WxRCFru3o3y3Z9K2_6N17EaTq5eAHP04AIgp');
//});

Route::get('/shows/bc-rising-undrip', function () {
  return redirect('/shows/bc-rising');
});

Route::get('/subscribe', function () {
  return redirect('/newsletterSignup');
});

Route::get('/newsletter', function () {
  return redirect('/newsletterSignup');
});

Route::get('/join', function () {
  return redirect('/newsletterSignup');
});

Route::get('/live-stream-guide', function () {
  return redirect('/live-streaming-guide');
});

Route::get('/live', function () {
  return redirect('/');
});

Route::get('/coffee', function () {
  return Inertia::location('https://www.buymeacoffee.com/hellorq');
});

Route::get('/stats', [AppSettingController::class, 'redirectStats']);

// setup on April 12, 2024 by tec21
Route::get('/bc-townhalls-2024-qr-code', function () {
  return redirect('/teams/bc-townhalls-2024');


// Routes For Testing
/////////////////////
//Route::get('/test-mail', function () {
//  Mail::raw('Hello world', function ($msg) {
//    $msg->to('test@example.com')->subject('Test Email');
//  });
});
