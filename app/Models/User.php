<?php

namespace App\Models;

use App\Notifications\VerifyEmailQueued;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Cashier\Subscription;
use Stripe\Order;

class User extends Authenticatable implements MustVerifyEmail {
  use Billable;
  use HasApiTokens;
  use HasFactory;
  use HasProfilePhoto;
  use Notifiable;
  use TwoFactorAuthenticatable;

  /**
   * The attributes that are mass assignable.
   *
   * @var string[]
   */
  protected $fillable = [
      'name',
      'email',
      'password',
      'role_id',
      'address1',
      'address2',
      'city',
      'province',
      'country',
      'postalCode',
      'phone',
      'timezone',
      'stripe_id',
      'isVip',
      'creatorNumber',
      'last_login_at',
      'terms_agreed_at',
      'invite_code',
      'stripe_id',
      'cookie_consent',
      'cookie_consent_at',
      'cookie_consent_expires_at',
      'is_banned',
      'ban_expires_at',
      'logins_count'
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array
   */
  protected $hidden = [
      'password',
      'remember_token',
      'two_factor_recovery_codes',
      'two_factor_secret',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [
      'email_verified_at'         => 'datetime',
      'last_login_at'             => 'datetime',
      'terms_agreed_at'           => 'datetime',
      'cookie_consent_expires_at' => 'datetime',
      'cookie_consent_at'         => 'datetime',
      'is_banned'                 => 'boolean',
      'ban_expires_at'            => 'datetime',
  ];

//    public function setPasswordAttribute($value)
//    {
//        $this->attributes['password'] = bcrypt($value);
//    }

  // Add this function to NOT send all
  // loggedin user database data back
  // to the browser.
  //
//    public function toArray()
//    {
//        return ([

//        ]);
//    }

  /**
   * The accessors to append to the model's array form.
   *
   * @var array
   */
  protected $appends = [
      'profile_photo_url',
  ];

  protected $attributes = [
      'isEditingShow_id'        => null,
      'isEditingTeam_id'        => null,
      'isEditingShowEpisode_id' => null,
  ];

  protected static function booted(): void {
    static::created(function ($user) {
      \App\Models\UserVideoSetting::create([
          'user_id'                => $user->id,
          'last_playback_position' => 0,
          'volume_setting'         => 1.0,
          'playback_speed'         => 1.0,
          'additional_settings'    => json_encode([]),
      ]);
    });
  }


  /**
   * Determine if the user has an active subscription.
   *
   * @param string $name
   * @return bool
   */
  public function hasActiveSubscription($name = 'default'): bool {
    return optional($this->subscription($name))->active();
  }

  /**
   * Determine if the user has a Stripe account.
   *
   * @return bool
   */
  public function hasAccount(): bool {
    return !is_null($this->stripe_id);
  }

  public function activeSubscriptions(): \Illuminate\Database\Eloquent\Relations\HasMany {
    return $this->subscriptions()->where('stripe_status', 'active');
  }

  public function isAdmin(): bool {
    return (bool) $this->isAdmin; // Assuming isAdmin is a column in your users table
  }

  public function isCreator(): bool {
    return $this->creator()->exists();
  }

  public function isNewsPerson(): bool {
    return $this->newsPerson()->exists();
  }

  public function isVip(): bool {
    return (bool) $this->isVip; // Assuming isVip is a column in your users table
  }

  /**
   * Check if the user has consented to cookies.
   *
   * @return bool
   */
  public function hasConsentedToCookies(): bool {
    return $this->cookie_consent && $this->cookie_consent_expires_at && $this->cookie_consent_expires_at->isFuture();
  }

  /**
   * Set cookie consent for the user.
   *
   * @param int $days
   * @return void
   */
  public function setCookieConsent($days = 365): void {
    $this->cookie_consent = true;
    $this->cookie_consent_at = Carbon::now();
    $this->cookie_consent_expires_at = Carbon::now()->addDays($days);
    $this->save();
  }
  /**
   * Send email verification notification (add to queue).
   */

//    public function sendEmailVerificationNotification() {
//        $this->notify(new VerifyEmailQueued);
//    }

  /**
   * Chat messages.
   */

  public function messages(): \Illuminate\Database\Eloquent\Relations\HasMany {
    return $this->hasMany(ChatMessage::class);
  }

  public function role(): \Illuminate\Database\Eloquent\Relations\HasOne {
    return $this->hasOne(Role::class);
  }

  public function creator(): \Illuminate\Database\Eloquent\Relations\HasOne {
    return $this->hasOne(Creator::class, 'user_id');
  }

  public function newsPerson(): \Illuminate\Database\Eloquent\Relations\HasOne {
    return $this->hasOne(NewsPerson::class);
  }

//    public function teams()
//    {
//        return $this->belongsToMany(Team::class, 'team_members', 'user_id', 'team_id')
//            ->as('teamMembers')
//            ->withPivot('active', 'added_by')
//            ->withTimestamps();
//    }

  public function teams(): \Illuminate\Database\Eloquent\Relations\BelongsToMany {
    return $this->belongsToMany(Team::class, 'team_members', 'user_id', 'team_id')
        ->using(TeamMember::class)
        ->as('teamMember')
        ->withPivot('active')
        ->withTimestamps();
  }

  public function teamManager(): \Illuminate\Database\Eloquent\Relations\BelongsToMany {
    return $this->belongsToMany(Team::class, 'team_managers', 'user_id', 'team_id')
        ->using(TeamManager::class)
        ->as('teamManager')
        ->withTimestamps();
//        ->addSelect('users.*', 'users.id as user_id', 'users.name', 'users.email', 'users.phone', 'users.profile_photo_path');
  }

  public function shows(): \Illuminate\Database\Eloquent\Relations\HasMany {
    return $this->hasMany(Show::class);
  }

  public function showEpisodes(): \Illuminate\Database\Eloquent\Relations\HasMany {
    return $this->hasMany(ShowEpisode::class);
  }

  public function newsStories(): \Illuminate\Database\Eloquent\Relations\HasMany {
    return $this->hasMany(NewsStory::class);
  }

  public function videos(): \Illuminate\Database\Eloquent\Relations\HasMany {
    return $this->hasMany(Video::class);
  }

  public function createdInviteCodes(): \Illuminate\Database\Eloquent\Relations\HasMany {
    return $this->hasMany(InviteCode::class, 'created_by');
  }

  public function claimedInviteCode(): \Illuminate\Database\Eloquent\Relations\HasOne {
    return $this->hasOne(InviteCode::class, 'claimed_by');
  }

  public function orders(): \Illuminate\Database\Eloquent\Relations\HasMany {
    return $this->hasMany(ProductOrder::class);
  }

  public function notifications(): \Illuminate\Database\Eloquent\Relations\HasMany {
    return $this->hasMany(Notification::class);
  }

  public function videoSettings(): \Illuminate\Database\Eloquent\Relations\HasOne {
    return $this->hasOne(UserVideoSetting::class);
  }

  /**
   * Get the secure notes for the user.
   */
  public function secureNotes(): \Illuminate\Database\Eloquent\Relations\HasMany {
    return $this->hasMany(SecureNote::class);
  }

  public function savedNewsRssFeedItemTemps(): \Illuminate\Database\Eloquent\Relations\HasMany {
    return $this->hasMany(NewsRssFeedItemTemp::class, 'saved_by_user_id');
  }

  public function archivedNewsRssFeedItems(): \Illuminate\Database\Eloquent\Relations\HasMany {
    return $this->hasMany(NewsRssFeedItemArchive::class, 'saved_by_user_id');
  }

  public function sentNewsPersonMessages(): HasMany {
    return $this->hasMany(NewsPersonMessage::class, 'sender_id');
  }

  public function sentCreatorMessages(): HasMany {
    return $this->hasMany(CreatorMessage::class, 'sender_id');
  }

}
