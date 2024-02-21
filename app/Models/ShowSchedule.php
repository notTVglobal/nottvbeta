<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShowSchedule extends Model
{
    use HasFactory;

  protected $table = 'show_schedule';

  protected $fillable = [
      'content_id',
      'content_type',
      'start_time',
      'end_time',
      'recurrence_flag',
      'recurrence_details_id',
      'status', // scheduled, live, completed, cancelled
      'priority', // defaults to 0
  ];

  public function content()
  {
    return $this->morphTo(__FUNCTION__, 'content_type', 'content_id');
  }

  public function recurrenceDetails()
  {
    return $this->belongsTo(RecurrenceDetails::class);
  }

}
