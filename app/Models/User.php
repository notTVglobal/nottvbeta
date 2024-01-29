<?php

namespace App\Models;

use App\Notifications\VerifyEmailQueued;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Stripe\Order;

class User extends Authenticatable implements MustVerifyEmail
{
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
        'stripe_id'
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
        'creatorNumber',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
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
        'isEditingShow_id' => null,
        'isEditingTeam_id' => null,
        'isEditingShowEpisode_id' => null,
    ];


    /**
     * Send email verification notification (add to queue).
     */

//    public function sendEmailVerificationNotification() {
//        $this->notify(new VerifyEmailQueued);
//    }

    /**
     * Chat messages.
     */

    public function messages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function role()
    {
        return $this->hasOne(Role::class);
    }

    public function creator()
    {
        return $this->hasOne(Creator::class, 'user_id');
    }

    public function newsPerson()
    {
        return $this->hasOne(NewsPerson::class);
    }

//    public function teams()
//    {
//        return $this->belongsToMany(Team::class, 'team_members', 'user_id', 'team_id')
//            ->as('teamMembers')
//            ->withPivot('active', 'added_by')
//            ->withTimestamps();
//    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_members', 'user_id', 'team_id')
            ->using(TeamMember::class)
            ->as('teamMember')
            ->withPivot('active')
            ->withTimestamps();
    }

    public function teamManager()
    {
        return $this->belongsToMany(Team::class, 'team_managers', 'user_id', 'team_id')
            ->using(TeamManager::class)
            ->as('teamManager')
            ->withTimestamps()
            ->addSelect('users.*', 'users.id as user_id', 'users.name', 'users.email', 'users.phone', 'users.profile_photo_path');
    }

    public function shows()
    {
        return $this->hasMany(Show::class);
    }

    public function showEpisodes()
    {
        return $this->hasMany(ShowEpisode::class);
    }

    public function newsStories()
    {
        return $this->hasMany(NewsStory::class);
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    public function inviteCodes(): \Illuminate\Database\Eloquent\Relations\HasMany {
        return $this->hasMany(InviteCode::class);
    }

    public function orders()
    {
        return $this->hasMany(ProductOrder::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

}
