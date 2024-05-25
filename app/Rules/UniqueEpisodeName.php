<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\DataAwareRule;
use App\Models\ShowEpisode;
use Illuminate\Contracts\Validation\Rule;

class UniqueEpisodeName implements Rule, DataAwareRule
{
  protected array $data = [];
  protected ?ShowEpisode $showEpisode;
  protected ?int $showId;

  public function setData($data)
  {
    $this->data = $data;
    return $this;
  }

  public function __construct($showEpisodeOrShowId)
  {
    if ($showEpisodeOrShowId instanceof ShowEpisode) {
      $this->showEpisode = $showEpisodeOrShowId;
      $this->showId = $showEpisodeOrShowId->show_id;
    } else {
      $this->showEpisode = null;
      $this->showId = $showEpisodeOrShowId;
    }
  }

  public function passes($attribute, $value)
  {
    if ($this->showEpisode && $value === $this->showEpisode->name) {
      return true;
    }

    return !ShowEpisode::where('show_id', $this->showId)
        ->where('name', $value)
        ->when($this->showEpisode, function ($query) {
          $query->where('id', '!=', $this->showEpisode->id);
        })
        ->exists();
  }

  public function message()
  {
    return 'The episode name must be unique for this show.';
  }
}