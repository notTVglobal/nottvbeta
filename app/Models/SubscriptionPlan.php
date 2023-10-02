<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'image_id', 'price_id', 'product_id'];

    // Define the relationship with the 'images' table
    public function image()
    {
        return $this->belongsTo(Image::class, 'image_id');
    }
}
