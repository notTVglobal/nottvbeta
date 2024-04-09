<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockchainTransaction extends Model
{
    protected $fillable = [
        'blockchain_id',
        'transaction_id',
        'status',
        // Add other fields as necessary
    ];

    // Define relationships, e.g., to Blockchain
    public function blockchain()
    {
        return $this->belongsTo(Blockchain::class);
    }

    public function status()
    {
        return $this->belongsTo(BlockchainTransactionStatus::class, 'status_id');
    }

    public function type()
    {
        return $this->belongsTo(BlockchainTransactionType::class, 'type_id');
    }
}
