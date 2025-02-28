<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model {
    protected $table = "products"; // Define the table name

    protected $fillable = [
        'code',
        'name',
        'model',
        'description',
        'photo',
        'price'
    ];
}
