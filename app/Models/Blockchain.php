<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blockchain extends Model
{
    // Define attributes in the fillable property if you plan to use mass assignment
    protected $fillable = ['name', 'shortcode', 'description', 'url', 'token_type'];

    /**
     * Get the team transfers associated with the blockchain.
     */
    public function teamTransfers()
    {
        return $this->hasMany(TeamTransfer::class);
    }
}
