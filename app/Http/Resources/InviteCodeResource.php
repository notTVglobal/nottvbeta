<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class InviteCodeResource extends JsonResource {
  /**
   * Transform the resource into an array.
   *
   * @param Request $request
   * @return array
   */
  public function toArray($request): array {

    return [
        'id'              => $this->id,
        'ulid'            => $this->ulid,
        'code'            => $this->code,
        'volume'          => $this->volume,
        'uses'            => $this->used_count,
        'claimed'         => $this->claimed,
        'claimed_by'      => $this->claimedBy->name,
        'claimed_at'      => $this->claimed_at,
      // Format expiry_date for HTML input
        'expiry_date'     => $this->expiry_date ?? null,
        'created_by_name' => $this->createdBy->name, // Assuming the User model has a 'name' attribute
        'created_by_id'   => $this->createdBy->id, // Assuming the User model has a 'name' attribute
        'role'            => $this->role->role ?? null, // Make sure to handle cases where role might be null
        'role_id'         => $this->role->id ?? null, // Make sure to handle cases where role might be null
      // Include other fields as needed
    ];
  }
}
