<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'image_id',
        'name',
        'description',
        'memberSpots',
        'totalSpots',
        'slug',
        'isBeingEditedByUser_id',
    ];

    public function getRouteKeyName() {
        return 'slug';
    }

    public function shows()
    {
        return $this->hasMany(Show::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'team_members')->using(TeamMember::class)
            ->as('teamMembers')
            ->withPivot('active')
            ->withTimestamps()
            ->select('id', 'name', 'email', 'phone', 'profile_photo_path');
    }

    public function teamLeader()
    {
        return $this->belongsTo(Creator::class);
    }

//    public function members()
//    {
//        return $this->belongsToMany(User::class, 'team_members')->using(TeamMember::class)
//            ->as('teamMembers')
//            ->withPivot('active')
//            ->withTimestamps()
//        ->select('name', 'email');
//    }

    public function image()
    {
        return $this->belongsTo(Image::class);
    }

//    public function appSetting()
//    {
//        return $this->belongsTo(AppSetting::class);
//    }


    public function appSetting(): BelongsTo
    {
        return $this->belongsTo(AppSetting::class)->withDefault([
            'cdn_endpoint' => 'https://development-nottv.sfo3.cdn.digitaloceanspaces.com',
        ]);
    }



}
