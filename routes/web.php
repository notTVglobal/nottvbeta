<?php

use App\Http\Controllers\Api\ChannelApiController;
use App\Http\Controllers\AppSettingController;
use App\Http\Controllers\ChangelogController;
use App\Http\Controllers\ChannelController;

use App\Http\Controllers\FlashController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\SubscriptionPlanController;
use App\Http\Controllers\TeamManagersController;
use App\Http\Controllers\TestMessageController;
use App\Http\Controllers\WelcomeController;
use App\Mail\VerifyMail;
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
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\NewsPersonController;
use App\Http\Controllers\NewsPostController;
use App\Http\Controllers\NewsroomController;
use App\Http\Controllers\NewsRssFeedController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\PostController;
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
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
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

//Route::get('/', function () {
//    return Inertia::render('Welcome', [
//        'canLogin' => Route::has('login'),
//        'canRegister' => Route::has('register'),
//    ]);
//})->name('home');

Route::get('/', [WelcomeController::class, 'index'])->name('home');


Route::get('/send-mail', function () {
   Mail::to('test@test.com')->queue(new VerifyMail());
});

Route::get('/home', function () {
    if (Auth::user()) {
        if (auth()->user()->creator) {
            return redirect('/dashboard');
        } return redirect('/stream');
    } return redirect('/');
});

//Route::get('/register', function () {
//    return redirect('/register');
//})->name('register');
//
//Route::get('/login', function () {
//    return redirect('/login');
//})->name('login');

Route::get('/email/verify', function () {
    return Inertia::render('Auth/VerifyEmail');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verify', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return Inertia::render('Auth/VerifySent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send2');
// Jetstream/Fortify came with an email verification method, but
// I can't figure out where the verification.send route is. And
// when I created this form.post to verification.send I got an
// error when running "php artisan route:cache" -> Unable to
// process route [email/notificationsent] for serialization.
// Another route has already been assigned name
// [verification.send] ~ tec21 (March 21, 2023)

Route::get('/testJob', function () {
    foreach (range(1,100) as $i) {
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
Route::get('/changelog', [ChangelogController::class, 'show'])->name('changelog.show');

// Public Pages

// News
Route::get('public/news', [NewsController::class, 'index'])
//        ->middleware('can:create,App\Models\NewsPerson')
    ->name('public.news.index');

Route::get('public/news/reporters', [NewsPersonController::class, 'index'])
//        ->middleware('can:create,App\Models\NewsPerson')
    ->name('public.newsPerson.index');

Route::get('public/news/reporter/{newsPerson}', [NewsPersonController::class, 'show'])
//        ->middleware('can:create,App\Models\NewsPerson')
    ->name('public.newsPerson.show');

// BEGIN ROUTES FOR
// Logged In Users
///////////

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/externalLink', function () {
        return Inertia::render('ExternalLink');
    })->name('externalLink');

    Route::get('/settings', function () {
        return Inertia::render('Settings');
    })->name('settings');

    Route::get('/stream', function () {
        return Inertia::render('Stream');
    })->name('stream');

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


// Dashboard
///////////
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->can('viewDashboard', 'App\Models\Creator')
        // tec21: it doesn't like the ->middleware option.
        // The ->can option works well.
        //
//        ->middleware('can:viewDashboard,creator')
        ->name('dashboard');


// Newsroom
///////////

    Route::get('/newsroom', [\App\Http\Controllers\NewsroomController::class, 'index'])
        ->middleware('can:viewAny,App\Models\NewsPerson')
        ->name('newsroom');

    Route::post('/newsroom/newsperson', [\App\Http\Controllers\NewsPersonController::class, 'store'])
//        ->middleware('can:create,App\Models\NewsPerson')
        ->name('newsperson.store');

    Route::put('/newsroom/newsperson/destroy', [\App\Http\Controllers\NewsPersonController::class, 'destroy'])
//        ->middleware('can:create,App\Models\NewsPerson')
        ->name('newsperson.destroy');

    // News Person Resource
//    Route::resource('newsPerson', \App\Http\Controllers\NewsPersonController::class);


// News
////////
    Route::resource('news', NewsPostController::class);

    Route::get('/news', [NewsPostController::class, 'index'])
//        ->can('view', 'App\Models\NewsPerson')
        ->name('news');

    Route::put('/newsroom/publish', [NewsroomController::class, 'publish'])
//        ->can('view', 'App\Models\NewsPerson')
        ->name('newsroom.publish');

    Route::post('/news/save', [NewsPostController::class, 'save'])
        ->name('news.save');

//    Route::get('/news/rss', [NewsRssFeedController::class, 'index'])
////        ->can('view', 'App\Models\NewsPerson')
//        ->name('news.rss.index');

    Route::resource('/feeds', NewsRssFeedController::class);

    Route::get('/feeds', [NewsRssFeedController::class, 'index'])
//        ->can('view', 'App\Models\NewsPerson')
        ->name('feeds.index');

    // this is a test
    Route::get('/rss2', [NewsRssFeedController::class, 'rss2'])
        ->name('rss2');
    Route::get('/rss2/create', [NewsRssFeedController::class, 'rss2create'])
        ->name('rss2create');
    Route::post('/rss2/store', [NewsRssFeedController::class, 'rss2store'])
        ->name('rss2store');
    Route::get('/rss2/{id}', [NewsRssFeedController::class, 'rss2show'])
        ->name('rss2show');
    Route::get('/rss2/{id}/edit', [NewsRssFeedController::class, 'rss2edit'])
        ->name('rss2edit');
    Route::post('/rss2/update', [NewsRssFeedController::class, 'rss2update'])
        ->name('rss2update');
    Route::post('/rss2/destroy', [NewsRssFeedController::class, 'rss2destroy'])
        ->name('rss2destroy');
    Route::get('/rss2/error', [NewsRssFeedController::class, 'rss2error'])
        ->name('rss2error');

//    Route::post('/feeds/save', [NewsRssFeedController::class, 'save'])
//        ->name('feeds.save');



// VIP
////////
    Route::put('/userAddToVip', [\App\Http\Controllers\UsersController::class, 'vipAdd'])
//        ->middleware('auth')->isAdmin
        ->name('user.vip.add');

    Route::put('/userRemoveFromVip', [\App\Http\Controllers\UsersController::class, 'vipRemove'])
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
    Route::get('/admin/channels', [ChannelController::class, 'index'])
        ->can('viewAdmin', 'App\Models\User')
        ->name('admin.channels');


// Shop
///////////
//    Route::resource('shop', ShopController::class);
    // List all products

    Route::get('/shop', [ShopController::class, 'index'])
        ->name('shop');

    Route::get('/shop/checkout', [ShopController::class, 'checkout'])
        ->name('checkout');

    Route::post('shop/summary', [ShopController::class, 'summary'])
        ->name('shop.summary');

    Route::redirect('shop/summary', '/shop');

    Route::resource('product', ProductController::class);

    Route::get('/api/products', [\App\Http\Controllers\Api\ProductController::class, 'index'])
        ->name('api.products');

    Route::get('/shop/products', [ProductController::class, 'index'])
        ->name('products');

    Route::get('/shop/product/{product}', [ProductController::class, 'show'])
    ->name('shop.product.show');

    Route::post('/shop/purchase', [StripeController::class, 'purchase']);

    Route::get('/upgrade', function () {
        if (auth()->user()->subscribed('default')) {
            return redirect('/stream');
        }
        return Inertia::render('Shop/Upgrade', [
            'intent' => auth()->user()->createSetupIntent(),
        ]);
    })->name('upgrade');

    Route::post('/upgrade', [StripeController::class, 'createCheckoutSession'])
        ->name('createCheckoutSession');

    Route::get('/shop/subscribe', [StripeController::class, 'subscribe'])
        ->name('shop.subscribe');

    Route::post('shop/subscribe', [StripeController::class, 'setupNewSubscription'])
        ->name('shop.subscribe.post');

    Route::get('/shop/subscription_success', [StripeController::class, 'subscriptionSuccess'])
        ->name('subscriptionSuccess');

    Route::post('payment/setup', [StripeController::class, 'initiateSetup']);
    Route::post('payment/initiate', [StripeController::class, 'initiatePayment']);
    Route::post('payment/complete', [StripeController::class, 'completePayment']);
    Route::post('payment/failure', [StripeController::class, 'failPayment']);

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

        Route::get('billing-portal', function (Request $request) {
            return $request->user()->redirectToBillingPortal(route('stream'));
        })->name('billingPortal');

    Route::get('billing-portal-access', [StripeController::class, 'getBillingPortalAccessUrl']);


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
        Route::put('/admin/subscription/{subscriptionPlan}', [SubscriptionPlanController::class, 'update'])->name('subscription-plans.update');

        // Delete method (for deleting)
        Route::delete('/admin/subscription/{subscriptionPlan}', [SubscriptionPlanController::class, 'destroy'])->name('subscription-plans.destroy');



// Library
///////////
    Route::get('/library', function () {
        return Inertia::render('Library');
    })->can('viewVip', 'App\Models\User')
        ->name('library');


// Schedule
///////////

    Route::get('/schedule', [ScheduleController::class, 'index'])
        ->name('schedule');


// Go Live
///////////

    Route::get('/golive', function () {
        return Inertia::render('GoLive');
    })->can('viewCreator', 'App\Models\User')
        ->name('golive');

// Invite
///////////
/// We'll make a public version of this page where a creator
/// enters a code they receive in their email to start the
/// sign up process.

    Route::get('/invite', function () {
        return Inertia::render('Invite');
    })->can('viewCreator', 'App\Models\User')
        ->name('invite');

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
    Route::post('/admin/getVideosFromEmbedCodes', [AdminController::class, 'getVideosFromEmbedCodes'])
        ->can('viewAdmin', 'App\Models\User')
        ->name('getVideosFromEmbedCodes');

    //// temp page to test Stores
    Route::get('/quiz', function () {
        return Inertia::render('QuizHome');
    })->can('viewAdmin', 'App\Models\User')
        ->name('quiz');

    //// SETTINGS
    Route::get('/admin/settings', [AdminController::class, 'settings'])
        ->can('viewAdmin', 'App\Models\User')
        ->name('admin.settings');
    //// SETTINGS - SAVE
    Route::put('/admin/settings', [AdminController::class, 'saveSettings'])
        ->can('viewAdmin', 'App\Models\User')
        ->name('admin.saveSettings');

    //// SHOWS - INDEX
    Route::get('/admin/shows', [AdminController::class, 'showsIndex'])
        ->can('viewAdmin', 'App\Models\User')
        ->name('admin.shows');

    //// EPISODES - INDEX
    Route::get('/admin/episodes', [AdminController::class, 'episodesIndex'])
        ->can('viewAdmin', 'App\Models\User')
        ->name('admin.episodes');

    //// TEAMS - INDEX
    Route::get('/admin/teams', [AdminController::class, 'teamsIndex'])
        ->can('viewAdmin', 'App\Models\User')
        ->name('admin.teams');

    //// INVITE CODES - INDEX
    Route::get('/admin/invite_codes', [AdminController::class, 'inviteCodes'])
        ->can('viewAdmin', 'App\Models\User')
        ->name('admin.inviteCodes');

    //// INVITE CODES - SAVE
    Route::post('/admin/invite_codes', [AdminController::class, 'saveInviteCodes'])
        ->can('viewAdmin', 'App\Models\User')
        ->name('admin.saveInviteCodes');

    //// INVITE CODES - EXPORT
    Route::get('/admin/export_invite_codes', [AdminController::class, 'exportInviteCodes'])
        ->can('viewAdmin', 'App\Models\User')
        ->name('admin.exportInviteCodes');

    //// DELETE USER
    Route::post('/admin/user/delete', [AdminController::class, 'deleteUser'])
        ->can('delete', 'App\Models\User')
        ->name('admin.deleteUser');

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
    Route::resource('teams', TeamsController::class);
//         Show a team
    Route::get('/teams/{team}', [TeamsController::class, 'show'])
        ->middleware('can:view,team')
        ->name('teams.show');


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


        // Teams - Part 2 (part 1 is outside the Creator Resources above)
        ///////////

        // List all teams
        Route::get('/teams', [TeamsController::class, 'index'])
            //        ->middleware('can:viewAny,App\Models\User')
            ->name('teams.index');

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

        // Edit team
        Route::get('/teams/{team}/edit', [TeamsController::class, 'edit'])
            ->name('teams.edit');

        // Team Members
        Route::resource('teamMembers', TeamMembersController::class);

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

    });

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


// Training
///////////
    Route::get('/training', function () {
        return Inertia::render('Training');
    })->can('viewCreator', 'App\Models\User')
        ->name('training');

// Shows
///////////
    // Shows resource
    Route::resource('shows', ShowsController::class);
    // Display shows index page
    Route::get('/shows', [ShowsController::class, 'index'])
        ->can('viewAny', 'App\Models\Show')
        ->name('shows');
    // Display shows manage page
    Route::get('/shows/{show}/manage', [ShowsController::class, 'manage'])
//        ->middleware('can:viewShowManagePage,show')
        ->name('shows.manage');
    // Display shows edit page
    Route::get('/shows/{show}/edit', [ShowsController::class, 'edit'])
        ->name('shows.edit');
    // Display shows create page
    Route::get('/shows/create', [ShowsController::class, 'create'])
        ->can('viewCreator', 'App\Models\User')
        ->name('shows.create');
    // Update show notes
    Route::post('/shows/notes', [ShowsController::class, 'updateNotes']);

    ///////////////
    // tec21: move these into the ShowEpisodeController section below.
    // Display episode create page
    Route::get('/shows/{show}/episode/create', [ShowsController::class, 'createEpisode'])
        ->name('shows.createEpisode');
    // Display episode manage page
    Route::get('/shows/{show}/episode/{showEpisode}/manage', [ShowsController::class, 'manageEpisode'])
        ->name('shows.showEpisodes.manageEpisode')
        ->scopeBindings();


// Show Episodes
///////////
    // Shows resource
    Route::resource('showEpisodes', ShowEpisodeController::class);
    // Display episodes index page
    Route::get('/shows/{show}/episodes', [ShowEpisodeController::class, 'index'])
        ->name('showEpisodes');
    // Display episode page
    Route::get('/shows/{show}/episode/{showEpisode}', [ShowEpisodeController::class, 'show'])
        ->name('shows.showEpisodes.show')
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

// Movies
///////////
    Route::resource('movies', MovieController::class);
    // List all movies
    Route::get('/movies/{movie}', [MovieController::class, 'show'])
        ->can('view', 'App\Models\Movie')
        ->name('movies.show');
    Route::get('/movies', [MovieController::class, 'index'])
        ->can('viewAny', 'App\Models\Movie')
        ->name('movies');
    // Display movie edit page
    Route::get('/movies/{movie}/edit', [MovieController::class, 'edit'])
        ->can('edit', 'App\Models\Movie')
        ->name('movies.edit');
//    // Upload movie
//    Route::post('/movies/upload', [MovieUploadController::class, 'store'])
//        ->can('viewCreator', 'App\Models\User')
//        ->name('moviesUpload.store');

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

    Route::post('/upload', [ImageController::class, 'store'])
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

    // Update user
    Route::put('/users', [UsersController::class, 'updateContact'])->name('users.updateContact');

// Chat
///////////
///
    Route::get('/chat/channels', [ChatController::class, 'channels']);
    Route::get('/chat/channel/{channelId}/messages', [ChatController::class, 'messages']);
    Route::post('/chat/message', [ChatController::class, 'newMessage']);
    Route::get('/chatTest', [TestMessageController::class, 'index']);

    Route::post('/chat/message', [ChatController::class, 'newMessage'])
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
    Route::post('/video/delete', [VideoUploadController::class, 'destroy'])
        ->can('delete', 'App\Models\Video')
        ->name('video.destroy');


// MistAPI
///////////
///

    Route::post('/mistapi', [VideoController::class, 'mistApi'])->name('mistApi');

});

// Extra Functions
//////////////////
///
    Route::post('/clear-flash', [\App\Http\Middleware\HandleInertiaRequests::class, 'clearFlash'])->name('flash.clear');

    Route::get('/notifications', [NotificationsController::class, 'index']);
    Route::put('/notifications/{id}/mark-as-read', [NotificationsController::class, 'markAsRead']);
    Route::delete('/notifications/{notification}', [NotificationsController::class, 'destroy']);
    Route::delete('/notifications', [NotificationsController::class, 'destroyAll']);



//Route::any('/{any}', function() {
//    return view('app');
//})->where('any', '.*');

// External Redirects
/////////////////////

Route::get('/subscribe', function () {
    return Inertia::location('https://99fd701b.sibforms.com/serve/MUIFAAAMUBfnlUf5rgaD2zSTE_76pHldyCCXhQvz-CBNZwd9lLYST4jcuwwsudQEHOkX1isAFHV6iXvtIepJSh5RkVrZY1wUQ5yaf1j6kWyzMJ75s2FZfHOZMdO7mkE-pDv96yW4bekMX67ZevIlWsjQvdDgEXUEKqfAvvQieIM3WxRCFru3o3y3Z9K2_6N17EaTq5eAHP04AIgp');
});
