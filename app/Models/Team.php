<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Team extends Model {
  use HasFactory;

  protected $fillable = [
      'user_id',
      'image_id',
      'name',
      'description',
      'memberSpots',
      'totalSpots',
      'team_members_profiles_are_visible',
      'slug',
      'isBeingEditedByUser_id',
      'team_leader',
      'team_status_id',
  ];

  protected $casts = [
      'team_members_profiles_are_visible' => 'boolean',
  ];

  public function getRouteKeyName(): string {
    return 'slug';
  }

  public function teamStatus(): BelongsTo {
    return $this->belongsTo(TeamStatus::class, 'team_status_id');
  }

  public function shows(): \Illuminate\Database\Eloquent\Relations\HasMany {
    return $this->hasMany(Show::class);
  }

  public function movies(): \Illuminate\Database\Eloquent\Relations\HasMany {
    return $this->hasMany(Movie::class);
  }

  public function managers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany {
    return $this->belongsToMany(User::class, 'team_managers', 'team_id', 'user_id')
        ->using(TeamManager::class)
        ->as('teamManagers')
        ->withTimestamps()
        ->addSelect('users.*', 'users.id as user_id', 'users.name', 'users.email', 'users.phone', 'users.profile_photo_path');
  }

  public function user(): BelongsTo {
    return $this->belongsTo(User::class);
  }

  public function members(): \Illuminate\Database\Eloquent\Relations\BelongsToMany {
    return $this->belongsToMany(User::class, 'team_members')
        ->using(TeamMember::class)
        ->as('teamMembers')
        ->withPivot('active')
        ->withTimestamps()
        ->select('id', 'name', 'email', 'phone', 'profile_photo_path');
  }

  public function teamLeader(): BelongsTo {
    return $this->belongsTo(Creator::class, 'team_leader');
  }

//    public function members()
//    {
//        return $this->belongsToMany(User::class, 'team_members')->using(TeamMember::class)
//            ->as('teamMembers')
//            ->withPivot('active')
//            ->withTimestamps()
//        ->select('name', 'email');
//    }

  public function image(): BelongsTo {
    return $this->belongsTo(Image::class);
  }

//    public function appSetting()
//    {
//        return $this->belongsTo(AppSetting::class);
//    }

  public function appSetting(): BelongsTo {
    return $this->belongsTo(AppSetting::class)->withDefault([
//            'cdn_endpoint' => 'https://development-nottv.sfo3.cdn.digitaloceanspaces.com',
    ]);
  }


}
