<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamTransferStatus extends Model
{
    // Define attributes in the fillable property if you plan to use mass assignment
    protected $fillable = ['name'];

    /**
     * Get the team transfers associated with this status.
     */
    public function teamTransfers()
    {
        return $this->hasMany(TeamTransfer::class, 'transfer_status_id');
    }
}
