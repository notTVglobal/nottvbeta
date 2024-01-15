<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamTransfer extends Model
{
    protected $fillable = [
        'team_id',
        'former_owner_user_id',
        'new_owner_user_id',
        'blockchain_id',
        'blockchain_transaction_id',
        'transfer_status_id',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function formerOwner()
    {
        return $this->belongsTo(User::class, 'former_owner_user_id');
    }

    public function newOwner()
    {
        return $this->belongsTo(User::class, 'new_owner_user_id');
    }

    public function blockchain()
    {
        return $this->belongsTo(Blockchain::class);
    }

    public function blockchainTransaction()
    {
        return $this->belongsTo(BlockchainTransaction::class);
    }

    public function transferStatus()
    {
        return $this->belongsTo(TeamTransferStatus::class, 'transfer_status_id');
    }
}
