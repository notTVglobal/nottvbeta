<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TeamMember extends Pivot {
  use HasFactory;

  /**
   * Indicates if the IDs are auto-incrementing.
   *
   * @var bool
   */

  // tec21: I removed the id's from the TeamMember table.
  // Put them back if that is necessary
  // and uncomment this line:
//    public $incrementing = true;

  protected $table = 'team_members';

  protected $fillable = [
      'team_id',
      'user_id',
      'active',
      'team_profile_is_public',
  ];

  protected $casts = [
      'team_profile_is_public' => 'boolean',
  ];


  // THE PREVIOUS RELATIONSHIPS (Feb.28, 2024)

  public function team(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
    return $this->belongsTo(Team::class);
  }

  /**
   * The users that belong to the team.
   */

  public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
    return $this->belongsTo(User::class);
  }

//    public function users()
//    {
//        return $this->belongsToMany(User::class, 'team_members', 'team_id', 'user_id');
//    }
}
