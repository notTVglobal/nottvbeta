<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageHash extends Model
{
    use HasFactory;
  // If your table name does not follow Laravel's naming convention,
  // you might need to explicitly set it like so:
  protected $table = 'image_hashes';

  // If your table does not have timestamps (created_at and updated_at columns),
  // set this property to false:
  public $timestamps = true;

  // Fillable attributes for mass assignment
  protected $fillable = [
      'image_id',
      'hash',
      'is_duplicate',
  ];

}
