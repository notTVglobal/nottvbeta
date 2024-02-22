<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecurrenceDetails extends Model
{
  public function showSchedule()
  {
    return $this->hasOne(ShowSchedule::class);
  }
}
