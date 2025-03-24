<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model {
    // The table associated with the model
    protected $table = 'cart';

    // Enable automatic timestamps
    public $timestamps = true;

    // The attributes that are mass assignable
    protected $fillable = [
        'user_id', 'product_id', 'quantity'
    ];

    // Define the relationship with the Product model
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
