<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
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
        'role_id',
        'email',
        'password',
        'address1',
        'address2',
        'city',
        'province',
        'country',
        'postalCode',
        'phone',
        'subscriptionStatus',
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
//        return ([]);
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
     * Chat messages.
     */

    public function messages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function roles()
    {
        return $this->hasOne(Role::class);
    }

    public function creator()
    {
        return $this->hasOne(Creator::class);
    }

    public function vip()
    {
        //
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
        return $this->belongsToMany(Team::class, 'team_members')
            ->using(TeamMember::class)
            ->as('teamMember')
            ->withPivot('active')
            ->withTimestamps();
    }

    public function shows()
    {
        return $this->hasMany(Shows::class);
    }

    public function showEpisodes()
    {
        return $this->hasMany(ShowEpisode::class);
    }

}
