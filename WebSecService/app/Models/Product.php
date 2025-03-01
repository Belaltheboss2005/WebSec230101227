<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model {
    // The table associated with the model
    protected $table = 'products';

    public $timestamps = false; // Disable automatic timestamps

    // The attributes that are mass assignable
    protected $fillable = [
        'code', 'name', 'price', 'model', 'description', 'photo'
    ];
}
